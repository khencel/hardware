<form action="{{ $action }}" method="POST">
    @csrf
    @isset($user)
        @method('PUT')
    @endisset

    @php
        $fields = [
            ['name' => 'firstname', 'label' => 'First Name'],
            ['name' => 'lastname', 'label' => 'Last Name'],
            ['name' => 'username', 'label' => 'Username'],
            ['name' => 'email', 'label' => 'Email', 'type' => 'email'],
        ];
    @endphp

    @foreach ($fields as $field)
        <x-form.input 
            name="{{ $field['name'] }}" 
            label="{{ $field['label'] }}" 
            type="{{ $field['type'] ?? 'text' }}" 
            :value="old($field['name'], $user ? $user->{$field['name']} : '')"
        />
    @endforeach


    @php $defaultPassword = old('password', Str::random(12)); @endphp
    <div class="mb-3">
        <label for="password" class="form-label">New Password</label>
        <div class="input-group">
            <input type="text" class="form-control" id="password" name="password" value="{{ $defaultPassword }}">
            <button class="btn btn-outline-secondary" type="button" id="generatePassword">Generate</button>
        </div>
        @error('password')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <script>
        document.getElementById('generatePassword').addEventListener('click', function() {
            const passwordField = document.getElementById('password');
            const newPassword = Math.random().toString(36).slice(-8);
            passwordField.value = newPassword;
        });
    </script>

    <x-form.select 
        name="role" 
        label="Role" 
        :options="$roles->pluck('name', 'id')" 
        :selected="old('role', $user ? $user->role_id : '')" 
    />

    <button type="submit" class="btn btn-primary w-100 mt-3">{{ $user ? 'Update' : 'Save' }}</button>
</form>
