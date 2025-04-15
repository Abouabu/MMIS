<div class="card">
  <div class="card-header">{{ __('Receptionist Login') }}</div>
  <div class="card-body">
      <form method="POST" action="{{ route('receptionist.login.submit') }}">
          @csrf
          <div class="mb-3">
              <label for="username" class="form-label">{{ __('Receptionist Name') }}</label>
              <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="name" value="{{ old('username') }}" required autofocus>
              @error('  name')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
              @enderror
          </div>
          <div class="mb-3">
              <label for="password" class="form-label">{{ __('Password') }}</label>
              <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
              @error('password')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
              @enderror
          </div>
          <div class="d-grid gap-2">
              <button type="submit" class="btn btn-primary">
                  {{ __('Login') }}
              </button>
          </div>
          @if ($errors->has('credentials'))
              <div class="alert alert-danger mt-3">
                  {{ $errors->first('credentials') }}
              </div>
          @endif
      </form>
  </div>
</div>