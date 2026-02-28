@extends('layout.app')

@section('content')

<div class="pagetitle">
    <h1>Expenses</h1>
    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">Expenses</li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex flex-column gap-1">
                            <h5 class="card-title mb-0">Expenses List</h5>
                            <span class="text-muted small">Total in range: <strong>₱{{ number_format($total, 2) }}</strong></span>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('expenses.create') }}" class="btn btn-sm btn-primary">
                                <i class="bi bi-plus-circle"></i> Add Expense
                            </a>
                        </div>
                    </div>

                    <form method="GET" class="row g-2 mb-3">
                        <div class="col-md-3">
                            <label class="form-label">From</label>
                            <input type="date" name="from" value="{{ request('from') }}" class="form-control">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">To</label>
                            <input type="date" name="to" value="{{ request('to') }}" class="form-control">
                        </div>
                            <div class="col-md-3">
                            <label class="form-label">Category</label>
                            <input type="text" name="category" value="{{ request('category') }}" class="form-control" placeholder="e.g. Rent">
                        </div>
                        <div class="col-md-3 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover table-bordered align-middle">
                            <thead>
                                <tr class="table-light">
                                    <th>Date</th>
                                    <th>Description</th>
                                    <th>Category</th>
                                    <th>Payment Method</th>
                                    <th class="text-end">Amount</th>
                                    <th style="width: 140px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($expenses as $expense)
                                    <tr>
                                        <td>{{ $expense->date?->format('M d, Y') ?? '—' }}</td>
                                        <td>{{ $expense->description }}</td>
                                        <td>{{ $expense->category ?? '—' }}</td>
                                        <td>{{ $expense->payment_method ?? '—' }}</td>
                                        <td class="text-end">₱{{ number_format($expense->amount, 2) }}</td>
                                        <td>
                                            <div class="d-flex flex-wrap gap-2">
                                                <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-warning btn-sm">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </a>
                                                <form action="{{ route('expenses.destroy', $expense) }}" method="POST" class="d-inline delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="bi bi-trash"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No expenses recorded yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $expenses->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

