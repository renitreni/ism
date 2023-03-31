<div class="col-6 col-md-4 mb-4">
    <form method="POST" action="{{ route('home.expenses.printable') }}">
        @csrf
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="fas fa-fw fa-money-bill"></i>
                            </button>
                            Total Expenses
                        </div>
                        <div class="h5 font-weight-bold text-gray-800">{{ $total }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-fw fa-money-bill fa-2x text-gray-300"></i>
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
                        <input type="text" id="expenses_totals" class="form-control" name="daterange"/>
                    @endif
                </div>
            </div>
        </div>
    </form>
    <script>
        window.addEventListener('fetch', event => {
            $('#expenses_totals').daterangepicker({
                opens: 'left',
            }, function(start, end, label) {
                $('#expenses_totals').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
                @this.set('dateRange', $('#expenses_totals').val())
            });

            @this.set('dateRange', $('#expenses_totals').val())
        });
    </script>
</div>
