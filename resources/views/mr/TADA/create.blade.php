@extends('mr.layouts.master')
@section('content')
<div class="container">
    <div class="page-inner">
        <div class="row">
            <div class="col-md-12">
                @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Add TA/DA Claim</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('mr.tada.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <!--Date-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label">Date of Travel</label>
                                        <input type="date" name="travel_date" class="form-control start-date" value="{{ old('travel_date', now()->format('Y-m-d')) }}">
                                        @error('travel_date')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Place vistted-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label">Place Visited</label>
                                        <input type="text" name="place_visited" class="form-control" placeholder="Enter place visited" value="{{ old('place_visited') }}">
                                        @error('place_visited')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Distance-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label">Distance Km</label>
                                        <input type="number" name="distance_km" class="form-control" placeholder="Enter distnace" value="{{ old('distance_km') }}">
                                        @error('distance_km')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Mode of Travel-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label">Mode of Travel</label>
                                        <select name="mode_of_travel" class="form-control">
                                            <option value="">Select Mode</option>
                                            <option value="Bus">Bus</option>
                                            <option value="Train">Train</option>
                                            <option value="Flight">Flight</option>
                                            <option value="Car">Car</option>
                                            <option value="Bike">Bike</option>
                                        </select>
                                        @error('mode_of_travel')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--TA Amount-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label">TA Amount</label>
                                        <input type="number" name="ta_amount" class="form-control" placeholder="Enter travel allowance" value="{{ old('ta_amount') }}">
                                        @error('ta_amount')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--DA Amount-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label">DA Amount</label>
                                        <input type="number" name="da_amount" class="form-control" placeholder="Enter daily allowance" value="{{ old('da_amount') }}">
                                        @error('da_amount')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Purpose-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label">Purpose of Visit</label>
                                        <textarea name="purpose" class="form-control" rows="2" placeholder="Enter purpose of travel">{{ old('purpose') }}</textarea>
                                        @error('purpose')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Remarks-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label">Remarks (Optional)</label>
                                        <textarea name="remarks" class="form-control" rows="2" placeholder="Any additional notes">{{ old('remarks') }}</textarea>
                                        @error('remarks')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Outstation Stag-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label">Outstation Stay</label>
                                        <textarea name="outstation_stay" class="form-control" rows="2" placeholder="Enterut outstation stay">{{ old('outstation_stay') }}</textarea>
                                        @error('outstation_stay')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Attachment-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label">Upload Attachment (Ticket / Bill)</label>
                                        <input type="file" name="attachment" class="form-control">
                                        @error('attachment')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="card-action">
                                <button type="submit" class="btn btn-success">Submit</button>
                                <button type="reset" class="btn btn-danger">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection