@extends('admin.layout.layout')
@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">{{ $title }}</h4>
                        <a href="{{ route('admin_merit_certificate') }}" class="btn btn-secondary btn-sm">Back</a>
                    </div>
                </div>
            </div>

            @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
            @endif

            <div class="card">
                <div class="card-body">
                    <p class="mb-3">
                        Generating for <strong>{{ count($people) }}</strong>
                        {{ $recipient_type === 'referee' ? 'Referee(s)' : 'Coach(es)' }}:
                        {{ $people->pluck('name')->implode(', ') }}
                    </p>

                    <form method="POST" action="{{ route('admin_merit_certificate_save') }}">
                        @csrf
                        <input type="hidden" name="recipient_type" value="{{ $recipient_type }}">
                        @foreach($people as $person)
                        <input type="hidden" name="recipient_ids[]" value="{{ $person->id }}">
                        @endforeach

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label>Template</label>
                                <select name="template" id="template" class="form-control" required>
                                    <option value="1" {{ (int)$default_template === 1 ? 'selected' : '' }}>Template 1 (Place + From date)</option>
                                    <option value="2" {{ (int)$default_template === 2 ? 'selected' : '' }}>Template 2 (Rules date)</option>
                                </select>
                            </div>
                            <div class="col-md-8 mb-3">
                                <label>Successfully Completed the</label>
                                <input type="text" name="course_title" class="form-control" value="{{ old('course_title') }}" placeholder="e.g. Coach Certification Course / Referee Seminar" required>
                            </div>
                            <div class="col-md-4 mb-3 tpl-1">
                                <label>Place (at ...)</label>
                                <input type="text" name="place" class="form-control" value="{{ old('place') }}" placeholder="City / Venue">
                            </div>
                            <div class="col-md-4 mb-3 tpl-1">
                                <label>From (date / duration)</label>
                                <input type="text" name="date_from" class="form-control" value="{{ old('date_from') }}" placeholder="e.g. 01-10-2026 to 05-10-2026">
                            </div>
                            <div class="col-md-4 mb-3 tpl-2">
                                <label>Rules &amp; Regulation on</label>
                                <input type="text" name="date_on" class="form-control" value="{{ old('date_on') }}" placeholder="e.g. 10-10-2026">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Issue Date</label>
                                <input type="date" name="issue_date" class="form-control" value="{{ old('issue_date', date('Y-m-d')) }}" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Generate Certificates</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script>
function toggleTpl() {
    var t = $('#template').val();
    $('.tpl-1').toggle(t === '1');
    $('.tpl-2').toggle(t === '2');
}
$(document).ready(function () {
    toggleTpl();
    $('#template').on('change', toggleTpl);
});
</script>
@endsection
