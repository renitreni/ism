<div class="col-6 col-md-4 mb-4">
    <form method="POST" action="{{ route('home.po.printable') }}">
        @csrf
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body" style="padding-bottom: .1rem;">
                <div class="row no-gutters align-items-center">
                    <div class="col">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="fas fa-file-download"></i>
                            </button>
                            Total PO
                        </div>
                        <div class="row no-gutters align-items-center">
                            <div class="col-auto">
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $total }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col ml-3 mr-3 d-flex flex-row mt-4">
                    <select class="form-control w-50" wire:model='filterBy'>
                        <option value="yearly">Yearly</option>
                        <option value="date_ranged">Date Ranged</option>
                    </select>
                    @if ($filterBy == 'yearly')
                        <select class="form-control" wire:model='year' name="year">
                            @foreach ($yearList as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                    @else
                        <input type="text" id="po_totals" class="form-control" name="daterange" />
                    @endif
                </div>
            </div>
        </div>
    </form>
    <script>
        window.addEventListener('fetch', event => {
            $('#po_totals').daterangepicker({
                opens: 'left',
            }, function(start, end, label) {
                $('#po_totals').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
                @this.set('dateRange', $('#po_totals').val())
            });

            @this.set('dateRange', $('#po_totals').val())
        });
    </script>
</div>
