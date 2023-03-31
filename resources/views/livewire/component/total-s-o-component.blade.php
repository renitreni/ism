<div class="col-6 col-md-4 mb-4">
    <div class="card border-left-warning shadow h-100 py-2">
        <div class="card-body" style="padding-bottom: .1rem;">
            <div class="row no-gutters align-items-center">
                <div class="col mr-2">
                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                        <a href="#" class="btn btn-sm btn-warning" data-toggle="modal"
                            data-target="#downloadTypeMdl">
                            <i class="fas fa-eye"></i>
                        </a>
                        Total SO
                    </div>
                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                        {{ $total }}
                    </div>
                </div>
                <div class="col-auto">
                    <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col ml-3 mr-3 d-flex flex-row">
                <select class="form-control w-50" wire:model='filterBy'>
                    <option value="yearly">Yearly</option>
                    <option value="date_ranged">Date Ranged</option>
                </select>
                @if ($filterBy == 'yearly')
                    <select class="form-control" wire:model='year'>
                        @foreach ($yearList as $year)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endforeach
                    </select>
                @else
                    <input type="text" id="so_totals" class="form-control" name="daterange"/>
                @endif
            </div>
        </div>
    </div>
    <script>
        window.addEventListener('fetch', event => {
            $('#so_totals').daterangepicker({
                opens: 'left',
            }, function(start, end, label) {
                $('#so_totals').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
                @this.set('dateRange', $('#so_totals').val())
            });

            @this.set('dateRange', $('#so_totals').val())
        });
    </script>
</div>