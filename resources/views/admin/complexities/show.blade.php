@extends('layouts.partials')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="row align-items-center mb-3">
            <div class="col-md-6">
                <h1 class="m-0">Complexity Level Details</h1>
            </div>
            <div class="col-md-6 text-md-right mt-2 mt-md-0">
                <a href="{{ route('complexities.index') }}" class="btn btn-outline-secondary">
                    <i class="fa fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow rounded">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title mb-0">Complexity Information</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped mb-0">
                                <tbody>
                                    <tr>
                                        <th class="bg-light" width="30%">Level</th>
                                        <td>{{ $complexity->level }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">Name</th>
                                        <td>{{ $complexity->name }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">Color</th>
                                        <td>
                                            <span class="badge text-white px-3 py-2" style="background-color: {{ $complexity->color }}">
                                                {{ $complexity->color }}
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <a href="{{ route('complexities.edit', $complexity->id) }}" class="btn btn-primary">
                            <i class="fa fa-edit"></i> Edit
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

{{-- Optional Styling --}}
<style>
    .card {
        border-radius: 0.75rem;
    }

    .card-title {
        font-size: 1.2rem;
        font-weight: 500;
    }

    .badge {
        font-size: 1rem;
        border-radius: 0.5rem;
    }
</style>
@endsection
