<div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label>Month</label>
                            <select wire:model="month" class="form-control">
                                <option value="01">January</option>
                                <option value="02">February</option>
                                <option value="03">March</option>
                                <option value="04">April</option>
                                <option value="05">May</option>
                                <option value="06">June</option>
                                <option value="07">July</option>
                                <option value="08">August</option>
                                <option value="09">September</option>
                                <option value="10">October</option>
                                <option value="11">November</option>
                                <option value="12">December</option>
                            </select>
                        </div>
                        <div class="col-6 mb-3">
                            <label>Year</label>
                            <input type="number" wire:model="year" class="form-control">
                        </div>
                        <div class="col-12">
                            <div class="row">
                                <div class="col-auto mb-2">
                                    Top #
                                </div>
                                <div class="col-auto mb-2">
                                    <strong>Quantity</strong>
                                </div>
                                <div class="col-9 mb-2">
                                    <strong>Product</strong>
                                </div>
                                <div class="col-12">
                                </div>
                                @foreach ($tops as $key => $value)
                                    <div class="col-auto">
                                        Top {{ ++$key }}
                                    </div>
                                    <div class="col-auto">
                                        {{ $value->qty_total }}
                                    </div>
                                    <div class="col-9">
                                        {{ $value->name }}
                                    </div>
                                    <div class="col-12">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
