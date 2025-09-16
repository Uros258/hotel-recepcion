<x-app-layout>
    <x-slot name="header"><h2 class="h4 fw-semibold">Sobe hotela</h2></x-slot>

    <div class="container py-4">
        @if(session('ok')) <div class="alert alert-success">{{ session('ok') }}</div> @endif

        <form class="row g-3 mb-3" method="GET" action="{{ route('sobe.manager') }}">
            <div class="col-auto">
                <label for="date" class="col-form-label">Datum</label>
            </div>
            <div class="col-auto">
                <input type="date" id="date" name="date" class="form-control" value="{{ $date }}">
            </div>
            <div class="col-auto">
                <button class="btn btn-outline-secondary">Prikaži</button>
            </div>
            <div class="col-auto ms-auto">
                <a href="{{ route('sobe.manager.edit', ['date' => $date]) }}" class="btn btn-primary">Izmeni</a>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead class="table-light">
                <tr>
                    <th>Tip sobe</th>
                    <th>Cena (RSD)</th>
                    <th>Ukupno u hotelu</th>
                    <th>Rezervisano</th>
                    <th>Slobodne sobe ({{ \Illuminate\Support\Carbon::parse($date)->format('d.m.Y') }})</th>
                </tr>
                </thead>
                <tbody>
                @foreach($rows as $row)
                    <tr>
                        <td>{{ $row->soba->tip_sobe }}</td>
                        <td>{{ number_format($row->soba->cena,2) }}</td>
                        <td>{{ $row->base + $row->occ - $row->free }}</td> 
                        <td>{{ $row->occ }}</td>
                        <td><span class="badge {{ $row->free ? 'bg-success':'bg-danger' }}">{{ $row->free }}</span></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
