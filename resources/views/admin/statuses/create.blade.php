@extends('layouts.partials')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="row">
            <div class="col-md-6">
                <h1>Create New Status</h1>
            </div>
            <div class="col-md-6 text-right">
                <a href="{{ route('admin.statuses.index') }}" class="btn btn-default">
                    <i class="fa fa-arrow-left"></i> Back to List
                </a>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Status Details</h3>
            </div>
            <form action="{{ route('admin.statuses.store') }}" method="POST">
                @csrf
                <div class="box-body">
                    <div class="form-group @error('name') has-error @enderror">
                        <label>Status Name *</label>
                        <input type="text" name="name" class="form-control" 
                               value="{{ old('name') }}" required>
                        @error('name')
                            <span class="help-block">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group @error('color') has-error @enderror">
                        <label>Color Code *</label>
                        <input type="color" name="color" class="form-control" 
                               value="{{ old('color', '#0073b7') }}" required
                               style="height: 38px; width: 70px; padding: 3px;">
                        @error('color')
                            <span class="help-block">{{ $message }}</span>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label>Preview:</label>
                        <span class="badge" id="colorPreview" 
                              style="background-color: {{ old('color', '#0073b7') }}">
                            Sample Status
                        </span>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-save"></i> Create Status
                    </button>
                </div>
            </form>
        </div>
    </section>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Simple color preview without external dependencies
        const colorInput = document.querySelector('input[name="color"]');
        const colorPreview = document.getElementById('colorPreview');
        
        colorInput.addEventListener('input', function() {
            colorPreview.style.backgroundColor = this.value;
        });
    });
</script>
@endsection