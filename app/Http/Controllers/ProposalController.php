<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Opportunity;
use App\Models\Proposal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProposalController extends Controller
{
    /**
     * Display a listing of proposals / quotations.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();

        $query = Proposal::query()
            ->with(['opportunity.customer', 'opportunity.owner'])
            ->whereHas('opportunity', function ($q) use ($user) {
                $q->accessibleBy($user);
            })
            ->latest('id');

        // Filter pencarian
        if ($request->filled('search')) {
            $search = trim((string) $request->input('search'));
            $query->where(function ($q) use ($search) {
                $q->where('proposal_number', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%")
                    ->orWhereHas('opportunity', function ($opp) use ($search) {
                        $opp->where('title', 'like', "%{$search}%")
                            ->orWhereHas('customer', function ($c) use ($search) {
                                $c->where('company_name', 'like', "%{$search}%");
                            });
                    });
            });
        }

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter Opportunity
        if ($request->filled('opportunity_id')) {
            $query->where('opportunity_id', $request->input('opportunity_id'));
        }

        $proposals = $query->paginate(12)->withQueryString();

        // Metrik Ringkasan Proposal
        $accessibleOppIds = Opportunity::query()->accessibleBy($user)->pluck('id');
        $allProposals = Proposal::whereIn('opportunity_id', $accessibleOppIds)->get();

        $totalProposalsCount = $allProposals->count();
        $totalProposalValue = (float) $allProposals->sum('amount');
        $acceptedValue = (float) $allProposals->where('status', 'accepted')->sum('amount');
        $avgGrossMargin = $allProposals->count() > 0 ? (float) $allProposals->avg('gross_margin') : 0.0;

        $opportunities = Opportunity::accessibleBy($user)->with('customer')->orderBy('title')->get();

        // Otomatis generate nomor proposal jika belum ada (Format: ELB-PROP-YYYYMM-XXXX)
        $monthYear = date('Ym');
        $latest = Proposal::where('proposal_number', 'like', "ELB-PROP-{$monthYear}-%")->count();
        $autoNumber = sprintf('ELB-PROP-%s-%04d', $monthYear, $latest + 1);
        $selectedOpportunityId = $request->input('opportunity_id');

        return view('pages.sales.proposals.index', compact(
            'proposals',
            'opportunities',
            'totalProposalsCount',
            'totalProposalValue',
            'acceptedValue',
            'avgGrossMargin',
            'autoNumber',
            'selectedOpportunityId'
        ));
    }

    /**
     * Show the form for creating a new proposal.
     * Redirects to index with open_create parameter so it opens directly in the modal.
     */
    public function create(Request $request): RedirectResponse
    {
        return redirect()->route('dashboard-admin.sales.proposals.index', [
            'open_create' => 1,
            'opportunity_id' => $request->input('opportunity_id'),
        ]);
    }

    /**
     * Store a newly created proposal in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'opportunity_id' => ['required', 'exists:opportunities,id'],
            'proposal_number' => ['required', 'string', 'max:100', 'unique:proposals,proposal_number'],
            'amount' => ['required', 'numeric', 'min:0'],
            'estimated_cost' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:'.implode(',', Proposal::STATUSES)],
            'submitted_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'document' => ['nullable', 'file', 'mimes:pdf,docx,xlsx,zip', 'max:20480'], // max 20MB
        ]);

        // Kalkulasi Gross Margin %: ((Amount - Cost) / Amount) * 100
        $amount = (float) $validated['amount'];
        $cost = (float) $validated['estimated_cost'];
        $grossMargin = $amount > 0 ? (($amount - $cost) / $amount) * 100 : 0.0;
        $validated['gross_margin'] = round($grossMargin, 2);

        // Upload berkas jika ada
        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('proposals', 'public');
            $validated['file_path'] = 'storage/'.$path;
        }

        // Jika status submitted dan belum ada submitted_at, isi waktu sekarang
        if ($validated['status'] === Proposal::STATUS_SUBMITTED && empty($validated['submitted_at'])) {
            $validated['submitted_at'] = now();
        }

        $proposal = Proposal::create($validated);

        // Jika status proposal diset ke 'submitted', otomatis perbarui stage opportunity jika masih di tahap awal
        if ($proposal->status === Proposal::STATUS_SUBMITTED) {
            $opp = $proposal->opportunity;
            if (in_array($opp->stage, ['qualification', 'need_analysis', 'tender_rfq'])) {
                $opp->update(['stage' => 'proposal_submitted']);
            }
        }

        return redirect()
            ->route('dashboard-admin.sales.proposals.index')
            ->with('success', 'Proposal / Quotation berhasil didaftarkan.');
    }

    /**
     * Display the specified proposal.
     */
    public function show(Proposal $proposal): View
    {
        $proposal->load(['opportunity.customer', 'opportunity.owner']);

        return view('pages.sales.proposals.show', compact('proposal'));
    }

    /**
     * Show the form for editing the specified proposal.
     */
    public function edit(Proposal $proposal): View
    {
        $user = auth()->user();
        $opportunities = Opportunity::accessibleBy($user)->with('customer')->orderBy('title')->get();

        return view('pages.sales.proposals.edit', compact('proposal', 'opportunities'));
    }

    /**
     * Update the specified proposal in storage.
     */
    public function update(Request $request, Proposal $proposal): RedirectResponse
    {
        $validated = $request->validate([
            'opportunity_id' => ['required', 'exists:opportunities,id'],
            'proposal_number' => ['required', 'string', 'max:100', 'unique:proposals,proposal_number,'.$proposal->id],
            'amount' => ['required', 'numeric', 'min:0'],
            'estimated_cost' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:'.implode(',', Proposal::STATUSES)],
            'submitted_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:1000'],
            'document' => ['nullable', 'file', 'mimes:pdf,docx,xlsx,zip', 'max:20480'],
        ]);

        $amount = (float) $validated['amount'];
        $cost = (float) $validated['estimated_cost'];
        $grossMargin = $amount > 0 ? (($amount - $cost) / $amount) * 100 : 0.0;
        $validated['gross_margin'] = round($grossMargin, 2);

        if ($request->hasFile('document')) {
            if ($proposal->file_path && file_exists(public_path($proposal->file_path))) {
                @unlink(public_path($proposal->file_path));
            }
            $path = $request->file('document')->store('proposals', 'public');
            $validated['file_path'] = 'storage/'.$path;
        }

        $proposal->update($validated);

        // Jika proposal diterima (accepted), tawarkan/update opportunity ke closed_won
        if ($proposal->status === Proposal::STATUS_ACCEPTED) {
            $proposal->opportunity->update(['stage' => 'closed_won']);
        }

        return redirect()
            ->route('dashboard-admin.sales.proposals.index')
            ->with('success', 'Data Proposal berhasil diperbarui.');
    }

    /**
     * Remove the specified proposal from storage.
     */
    public function destroy(Proposal $proposal): RedirectResponse
    {
        if ($proposal->file_path && file_exists(public_path($proposal->file_path))) {
            @unlink(public_path($proposal->file_path));
        }

        $proposal->delete();

        return redirect()
            ->route('dashboard-admin.sales.proposals.index')
            ->with('success', 'Proposal berhasil dihapus.');
    }
}
