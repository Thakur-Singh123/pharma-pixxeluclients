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
                        <h4 class="card-title">Edit TA/DA Claim</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('mr.tada.update', $tada_detail->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <!--Date-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label">Date of Travel</label>
                                        <input type="date" name="travel_date" class="form-control start-date" value="{{ old('travel_date',$tada_detail->travel_date) }}">
                                        @error('travel_date')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Place vistted-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label">Place Visited</label>
                                        <input type="text" name="place_visited" class="form-control" placeholder="Enter place visited" value="{{ old('place_visited',$tada_detail->place_visited) }}">
                                        @error('place_visited')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Distance-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label">Distance Km</label>
                                        <input type="number" name="distance_km" class="form-control" placeholder="Enter distance" value="{{ old('distance_km',$tada_detail->distance_km) }}">
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
                                            <option value="Bus" @if($tada_detail->mode_of_travel == 'Bus') selected @endif>Bus</option>
                                            <option value="Train" @if($tada_detail->mode_of_travel == 'Train') selected @endif>Train</option>
                                            <option value="Flight" @if($tada_detail->mode_of_travel == 'Flight') selected @endif>Flight</option>
                                            <option value="Car" @if($tada_detail->mode_of_travel == 'Car') selected @endif>Car</option>
                                            <option value="Bike" @if($tada_detail->mode_of_travel == 'Bike') selected @endif>Bike</option>
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
                                        <input type="number" name="ta_amount" class="form-control" placeholder="Enter travel allowance" value="{{ old('ta_amount',$tada_detail->ta_amount) }}">
                                        @error('ta_amount')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--DA Amount-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label">DA Amount</label>
                                        <input type="number" name="da_amount" class="form-control" placeholder="Enter daily allowance" value="{{ old('da_amount',$tada_detail->da_amount) }}">
                                        @error('da_amount')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Purpose-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label">Purpose of Visit</label>
                                        <textarea name="purpose" class="form-control" rows="2" placeholder="Enter purpose of travel">{{ old('purpose',$tada_detail->purpose_of_visit) }}</textarea>
                                        @error('purpose')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Remarks-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label">Remarks (Optional)</label>
                                        <textarea name="remarks" class="form-control" rows="2" placeholder="Any additional notes">{{ old('remarks',$tada_detail->remarks) }}</textarea>
                                        @error('remarks')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Outstation Stag-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label">Outstation Stay</label>
                                        <textarea name="outstation_stay" class="form-control" rows="2" placeholder="Enter outstation stay">{{ old('outstation_stay', $tada_detail->outstation_stay) }}</textarea>
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
                                <!--Check if attachment exists or not-->
                                <div class="d-flex justify-content-end">
                                    @if($tada_detail->attachment)
                                        <img src="{{ asset('public/uploads/ta_da/' . $tada_detail->attachment) }}" style="width:200px; height:100px; object-fit:cover; margin-right:100px;" alt="TA/DA Attachment">
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>
                            <div class="card-action">
                                <button type="submit" class="btn btn-success">Update</button>
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