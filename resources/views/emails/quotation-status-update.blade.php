@component('mail::message')
# Quotation Request Status Update

Dear {{ $quotation->name }},

Your quotation request for **{{ $quotation->product_name }}** has been updated.

The status has been changed from **{{ $oldStatus }}** to **{{ $newStatus }}**.

@if($notes)
**Additional Notes:**
{{ $notes }}
@endif

**Request Details:**
- Product: {{ $quotation->product_name }}
- Hospital: {{ $quotation->hospital_name }}
- Reference Number: {{ $quotation->id }}

If you have any questions, please don't hesitate to contact us.

Thanks,<br>
Medical & Hospital Resources Health Care, Inc.
@endcomponent 