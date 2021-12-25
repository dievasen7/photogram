<div class="card mb-3" style="max-width: 540px;">
  <div class="row g-0">
    <div class="col-md-4">
      <img src="{{ $follower->details->profile_photo }}" class="img-fluid rounded-start" alt="profile photo">
    </div>
    <div class="col-md-8">
      <div class="card-body">
        <h5 class="card-title">{{ $follower->details->display_name }} {{ __('started following you') }}</h5>
        <p class="card-text">{{ $follower->details->bio }}</p>
        <p class="card-text"><small class="text-muted">{{ $date->format('H:i, d.m.Y') }}</small></p>
      </div>
    </div>
  </div>
</div>