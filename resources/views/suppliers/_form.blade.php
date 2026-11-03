@csrf

<div class="form-group">
    <label for="name">Nama Supplier <span>*</span></label>
    <input
        id="name"
        type="text"
        name="name"
        value="{{ old('name', $supplier->name ?? '') }}"
        placeholder="Contoh: Budi Santoso"
        maxlength="255"
        required>
    @error('name')
    <small class="error">{{ $message }}</small>
    @enderror
</div>

<div class="form-group">
    <label for="company">Nama Perusahaan</label>
    <input
        id="company"
        type="text"
        name="company"
        value="{{ old('company', $supplier->company ?? '') }}"
        placeholder="Contoh: PT Sumber Makmur"
        maxlength="255">
    @error('company')
    <small class="error">{{ $message }}</small>
    @enderror
</div>

<div class="form-group">
    <label for="email">Email</label>
    <input
        id="email"
        type="email"
        name="email"
        value="{{ old('email', $supplier->email ?? '') }}"
        placeholder="supplier@email.com"
        maxlength="255">
    @error('email')
    <small class="error">{{ $message }}</small>
    @enderror
</div>

<div class="form-group">
    <label for="phone">Nomor Telepon</label>
    <input
        id="phone"
        type="text"
        name="phone"
        value="{{ old('phone', $supplier->phone ?? '') }}"
        placeholder="Contoh: 081234567890"
        maxlength="30">
    @error('phone')
    <small class="error">{{ $message }}</small>
    @enderror
</div>

<div class="form-group">
    <label for="address">Alamat</label>
    <textarea
        id="address"
        name="address"
        rows="3"
        placeholder="Alamat supplier">{{ old('address', $supplier->address ?? '') }}</textarea>
    @error('address')
    <small class="error">{{ $message }}</small>
    @enderror
</div>

<div class="form-group">
    <label for="notes">Catatan</label>
    <textarea
        id="notes"
        name="notes"
        rows="3"
        placeholder="Catatan tambahan (opsional)">{{ old('notes', $supplier->notes ?? '') }}</textarea>
    @error('notes')
    <small class="error">{{ $message }}</small>
    @enderror
</div>

<div class="form-actions">
    <a class="cancel-button" href="{{ route('suppliers.index') }}">
        Batal
    </a>

    <button class="button" type="submit">
        {{ $submitLabel ?? 'Simpan Supplier' }}
    </button>
</div>