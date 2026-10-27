<?php

namespace App\Http\Requests;

use DateTimeZone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateVisitorSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('manage visitor settings') === true;
    }

    public function rules(): array
    {
        return [
            'archive_enabled' => ['required', 'boolean'],
            'archive_interval' => ['required', Rule::in(['daily', 'weekly', 'monthly'])],
            'archive_day' => ['nullable', 'required_if:archive_interval,weekly', 'integer', 'between:1,7'],
            'archive_month_day' => ['nullable', 'required_if:archive_interval,monthly', 'integer', 'between:1,31'],
            'archive_time' => ['required', 'date_format:H:i'],
            'retention_value' => ['required', 'integer', 'min:1', 'max:3650'],
            'retention_unit' => ['required', Rule::in(['days', 'weeks', 'months'])],
            'auto_delete_enabled' => ['required', 'boolean'],
            'delete_after_archive' => ['required', 'boolean'],
            'timezone' => ['required', Rule::in(DateTimeZone::listIdentifiers())],
        ];
    }

    public function messages(): array
    {
        return [
            'archive_day.required_if' => 'Hari pelaksanaan wajib dipilih untuk interval mingguan.',
            'archive_month_day.required_if' => 'Tanggal pelaksanaan wajib dipilih untuk interval bulanan.',
            'archive_time.date_format' => 'Waktu arsip harus menggunakan format jam dan menit.',
        ];
    }
}
