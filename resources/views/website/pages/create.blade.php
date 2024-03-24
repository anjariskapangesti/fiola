@extends('website.layouts.main', ['title' => 'Create Folder'])

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <h5 class="card-header">Create Folder</h5>
                    <div class="card-body demo-vertical-spacing demo-only-element">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="email" class="form-control" id="exampleFormControlInput1"
                                placeholder="name@example.com" />
                            <label for="exampleFormControlInput1">Email address</label>
                        </div>
                        <div class="form-floating form-floating-outline mb-4">
                            <select class="form-select" id="exampleFormControlSelect1" aria-label="Default select example">
                                <option selected>Open this select menu</option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                            <label for="exampleFormControlSelect1">Example select</label>
                        </div>
                        <div class="form-floating form-floating-outline mb-4">
                            <input class="form-control" list="datalistOptions" id="exampleDataList"
                                placeholder="Type to search..." />
                            <datalist id="datalistOptions">
                                <option value="San Francisco"></option>
                                <option value="New York"></option>
                                <option value="Seattle"></option>
                                <option value="Los Angeles"></option>
                                <option value="Chicago"></option>
                            </datalist>
                            <label for="exampleDataList">Datalist example</label>
                        </div>
                        <div class="form-floating form-floating-outline mb-4">
                            <select multiple class="form-select h-px-100" id="exampleFormControlSelect2"
                                aria-label="Multiple select example">
                                <option selected>Open this select menu</option>
                                <option value="1">One</option>
                                <option value="2">Two</option>
                                <option value="3">Three</option>
                            </select>
                            <label for="exampleFormControlSelect2">Example multiple select</label>
                        </div>
                        <div class="form-floating form-floating-outline mb-4">
                            <textarea class="form-control h-px-100" id="exampleFormControlTextarea1" placeholder="Comments here..."></textarea>
                            <label for="exampleFormControlTextarea1">Example textarea</label>
                        </div>
                        <div class="form-floating form-floating-outline mb-4">
                            <input class="form-control" type="text" id="exampleFormControlReadOnlyInput1"
                                placeholder="Readonly input here..." readonly />
                            <label for="exampleFormControlReadOnlyInput1">Read only without
                                value</label>
                        </div>
                        <div class="form-floating form-floating-outline">
                            <input type="text" readonly class="form-control-plaintext"
                                id="exampleFormControlReadOnlyInputPlain1" value="email@example.com" />
                            <label for="exampleFormControlReadOnlyInputPlain1">Read only with
                                value</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
@endpush

@push('scripts')
@endpush
