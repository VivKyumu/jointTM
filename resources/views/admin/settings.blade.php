@extends('layouts.partials')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>System Settings</h1>
    </section>

    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">General Settings</h3>
            </div>
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                <div class="box-body">
                    <div class="form-group">
                        <label for="site_name">Site Name</label>
                        <input type="text" class="form-control" id="site_name" name="site_name" 
                               value="{{ old('site_name', config('app.name')) }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="items_per_page">Items Per Page</label>
                        <input type="number" class="form-control" id="items_per_page" name="items_per_page" 
                               value="{{ old('items_per_page', 10) }}" min="5" max="100" required>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Save Settings</button>
                </div>
            </form>
        </div>
    </section>
</div>
@endsection