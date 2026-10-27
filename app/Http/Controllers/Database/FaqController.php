<?php

namespace App\Http\Controllers\Database;

use App\Http\Controllers\Controller;
use App\Models\Database\Faq;
use App\Models\Database\SectionFaq;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;

class FaqController extends Controller
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:lihat faq', only: ['index']),
            new Middleware('permission:buat faq', only: ['create', 'store']),
            new Middleware('permission:edit faq', only: ['edit', 'update']),
            new Middleware('permission:hapus faq', only: ['destroy']),
            new Middleware('permission:edit label faq', only: ['storeOrUpdate']),
        ];
    }

    public function index(Request $request)
    {
        $faqs = Faq::orderBy('order', 'asc')->get();
        $label = SectionFaq::first();
        return view('pages.faqs.index', compact('faqs', 'label'));
    }

    public function create()
    {
        $nextOrder = (Faq::max('order') ?? 0) + 1;
        return view('pages.faqs.create', compact('nextOrder'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'order' => 'nullable|integer|min:1',
        ]);

        $nextOrder = (Faq::max('order') ?? 0) + 1;
        $order = $request->filled('order') ? (int) $request->order : $nextOrder;

        Faq::create([
            'question' => $request->question,
            'answer' => $request->answer,
            'order' => $order,
        ]);

        return redirect()->route('dashboard-admin.faqs.index')
            ->with('success', 'FAQ berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $faq = Faq::findOrFail($id);
        $otherFaqs = Faq::where('id', '!=', $id)->orderBy('order', 'asc')->get();
        return view('pages.faqs.edit', compact('faq', 'otherFaqs'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'question' => 'required|string|max:255',
            'answer' => 'required|string',
            'order' => 'required|integer|min:1',
            'swap_with_id' => 'nullable|exists:faqs,id',
        ]);

        DB::transaction(function () use ($request, $id) {
            $faq = Faq::lockForUpdate()->findOrFail($id);
            $currentOrder = (int) $faq->order;
            $newOrder = (int) $request->input('order', $currentOrder);
            $swapId = $request->input('swap_with_id');

            if ($swapId && (int) $swapId !== (int) $id) {
                $targetFaq = Faq::lockForUpdate()->findOrFail($swapId);
                $targetOrder = (int) $targetFaq->order;

                // Tukar urutan antara FAQ ini dengan FAQ terpilih
                $targetFaq->order = $currentOrder;
                $targetFaq->save();

                $faq->order = $targetOrder;
            } elseif ($newOrder !== $currentOrder) {
                // Jika input order diubah langsung dan sama dengan order FAQ lain, tukar posisi
                $targetFaq = Faq::lockForUpdate()
                    ->where('id', '!=', $id)
                    ->where('order', $newOrder)
                    ->first();

                if ($targetFaq) {
                    $targetFaq->order = $currentOrder;
                    $targetFaq->save();
                }

                $faq->order = $newOrder;
            }

            $faq->question = $request->question;
            $faq->answer = $request->answer;
            $faq->save();
        });

        return redirect()->route('dashboard-admin.faqs.index')
            ->with('success', 'FAQ berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $faq = faq::findOrFail($id);
        $faq->delete();
        return redirect()->route('dashboard-admin.faqs.index')->with('success', 'faq berhasil dihapus.');
    }

    public function storeOrUpdate(Request $request)
    {
        $data = $request->validate([
            'title' => 'nullable|string',
            'subTitle' => 'nullable|string',
            'description' => 'nullable|string',
        ]);

        SectionFaq::updateOrCreate(['id' => 1], $data);

        return redirect()->route('dashboard-admin.faqs.index')
            ->with('success', 'Label berhasil diperbarui.')
            ->with('activeTab', 'tab-label');
    }
}
