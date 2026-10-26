@php
    $selectedPermissions = old('permissions', $role ? $role->permissions->pluck('name')->toArray() : []);
@endphp

<div class="row g-3">
    <div class="col-12">
        <label class="form-label">Role name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $role?->name) }}" required>
    </div>

    <div class="col-12">
        <label class="form-label">Permissions</label>
        <div class="row g-2">
            @foreach ($permissions as $permission)
                <div class="col-md-4">
                    <label class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="permissions[]"
                            value="{{ $permission->name }}"
                            {{ in_array($permission->name, $selectedPermissions) ? 'checked' : '' }}>
                        <span class="form-check-label">{{ $permission->name }}</span>
                    </label>
                </div>
            @endforeach
        </div>
    </div>
</div>
