<div class="row g-3">
    <div class="col-md-6"><label class="form-label">Name</label><input name="name" class="form-control"
            value="{{ old('name', $teamMember->name ?? '') }}" required></div>
    <div class="col-md-6"><label class="form-label">Designation</label><input name="designation" class="form-control"
            value="{{ old('designation', $teamMember->designation ?? '') }}" required></div>
    <div class="col-12"><label class="form-label">Bio</label>
        <textarea name="bio" class="form-control" rows="3">{{ old('bio', $teamMember->bio ?? '') }}</textarea>
    </div>
    <div class="col-md-4"><label class="form-label">Facebook URL</label><input name="facebook_url" class="form-control"
            value="{{ old('facebook_url', $teamMember->facebook_url ?? '') }}"></div>
    <div class="col-md-4"><label class="form-label">LinkedIn URL</label><input name="linkedin_url" class="form-control"
            value="{{ old('linkedin_url', $teamMember->linkedin_url ?? '') }}"></div>
    <div class="col-md-4"><label class="form-label">Twitter URL</label><input name="twitter_url" class="form-control"
            value="{{ old('twitter_url', $teamMember->twitter_url ?? '') }}"></div>
    <div class="col-md-4"><label class="form-label">Sort Order</label><input name="sort_order" type="number"
            class="form-control" value="{{ old('sort_order', $teamMember->sort_order ?? 0) }}"></div>
    <div class="col-md-4 form-check mt-4"><input class="form-check-input" type="checkbox" name="status" value="1"
            {{ old('status', $teamMember->status ?? true) ? 'checked' : '' }}><label
            class="form-check-label">Active</label></div>
    <div class="col-12"><label class="form-label">Photo</label><input type="file" name="photo"
            class="form-control"></div>
</div>
