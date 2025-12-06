@extends('layouts.app')

@section('title', 'FAQ')

@section('content')

<div class="section-body">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div class="header-action">
                <h1 class="page-title">FAQs</h1>
                <ol class="breadcrumb page-breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">FAQs</li>
                </ol>
            </div>
            <ul class="nav nav-tabs page-header-tab">
                @can('FAQ Create')
                <li class="nav-item">
                    <a class="btn btn-info" href="{{ route('faqs.create') }}">
                        <i class="fa fa-plus"></i> Add New FAQ
                    </a>
                </li>
                @endcan
            </ul>
        </div>
    </div>
</div>

<div class="section-body mt-4">
    <div class="container-fluid">
        <div class="tab-content">
            <div class="tab-pane active" id="faq-all">
                <div class="card mt-4">
                    <div class="card-body">
                        <div class="alert alert-info mb-3">
                            <i class="fa fa-arrows-alt"></i> Drag and drop FAQs to reorder.
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped table_custom border-style spacing5">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width:40px;"></th>
                                        <th>Sl.no</th>
                                        <th>Question</th>
                                        <th>Answer</th>
                                        <th>Visibility</th>
                                        <th>Order</th>
                                        <th>Created At</th>
                                        @canany(['FAQ Edit', 'FAQ Delete'])
                                        <th>Action</th>
                                        @endcanany
                                    </tr>
                                </thead>
                                <tbody id="faq-sortable">
                                    @forelse($faqs as $faq)
                                        <tr data-id="{{ $faq->id }}" class="sortable-row">
                                            <td class="text-center cursor-grab"><i class="fa fa-bars text-muted"></i></td>
                                            <td>{{ $loop->iteration }}</td>
                                            <td class="text-wrap">{{ Str::limit(strip_tags($faq->question), 100) }}</td>
                                            <td class="text-wrap">{!! Str::limit($faq->answer, 120) !!}</td>
                                            <td>{!! check_status($faq->is_visible) !!}</td>
                                            <td>{{ $faq->order }}</td>
                                            <td>{{ format_datetime($faq->created_at) }}</td>

                                            @canany(['FAQ Edit', 'FAQ Delete'])
                                            <td>
                                                @can('FAQ Edit')
                                                <a class="btn btn-icon btn-sm" href="{{ route('faqs.edit', $faq->id) }}" alt="edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                @endcan

                                                @can('FAQ Delete')
                                                <form action="{{ route('faqs.destroy', $faq->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-icon btn-sm" onclick="return confirm('Are you sure you want to delete this FAQ?')" type="submit">
                                                        <i class="fa fa-trash-o text-danger"></i>
                                                    </button>
                                                </form>
                                                @endcan
                                            </td>
                                            @endcanany
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted">No FAQs found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<!-- jQuery UI for Dragging -->
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.min.js"></script>

<script>
$(function() {
    $('#faq-sortable').sortable({
        handle: '.cursor-grab',
        update: function(event, ui) {
            let order = [];
            $('#faq-sortable tr').each(function(index) {
                order.push({
                    id: $(this).data('id'),
                    position: index + 1
                });
            });

            $.ajax({
                type: 'POST',
                url: '{{ route("faqs.reorder") }}',
                data: {
                    _token: '{{ csrf_token() }}',
                    order: order
                },
                success: function(response) {
                    if(response.status === 'success') {
                        toastr.success('FAQ order updated successfully!');
                    } else {
                        toastr.error('Something went wrong.');
                    }
                },
                error: function() {
                    toastr.error('Unable to save order.');
                }
            });
        }
    }).disableSelection();
});
</script>
@endsection
