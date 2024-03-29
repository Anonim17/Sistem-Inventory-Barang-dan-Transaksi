@extends('layouts.dashboard')

@section('description', 'Halaman yang berisi daftar data barang dan stok yang dibuat.')

@section('route_name', 'Stok Barang')

@section('content')
@can('isAdmin')
<div class="row justify-content-end">
    <div class="col-auto">
        <a href="javascript:void(0)" class="btn btn-sm btn-secondary mb-3" onclick="printContent()">
            Cetak
        </a>
    </div>
</div>
@endcan
{{-- Modal --}}
<div class="modal fade" id="filterModal" tabindex="-1" aria-labelledby="filterModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form class="modal-content" action="{{ route('stocks.index') }}" method="GET">
            <div class="modal-header">
                <h5 class="modal-title" id="filterModalLabel">Sortir / Saring Stok</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input type="hidden" name="keyword" value="{{ $input['keyword'] }}">
                    <label for="order_by">Kolom Urut</label>
                    <select class="form-control form-control-sm" id="order_by" name="order_by">
                        <option value="part_number" {{ $input['order_by'] === 'part_number' ? 'selected' : '' }}>
                            ID Barang
                        </option>
                        <option value="description" {{ $input['order_by'] === 'description' ? 'selected' : '' }}>
                            Deskripsi
                        </option>
                        <option value="price" {{ $input['order_by'] === 'price' ? 'selected' : '' }}>
                            Harga
                        </option>

                    </select>
                </div>
                <div class="form-group">
                    <label for="order_direction">Arah Urut</label>
                    <select name="order_direction" id="order_direction" class="form-control form-control-sm">
                        <option value="asc" {{ $input['order_direction'] === 'asc' ? 'selected' : '' }}>
                            Naik
                        </option>
                        <option value="desc" {{ $input['order_direction'] === 'desc' ? 'selected' : '' }}>
                            Turun
                        </option>
                    </select>
                </div>

                <div class="row">
                    <div class="col-12">
                        <label for="start_stock">Stok</label>
                    </div>
                </div>
                <div class="row align-items-center">
                    <div class="col">
                        <div class="form-group m-0">
                            <input type="number" class="form-control" value="{{ $input['start_stock'] }}" id="start_stock" name="start_stock">
                        </div>
                    </div>
                    <div class="col-auto">
                        -
                    </div>
                    <div class="col">
                        <div class="form-group m-0">
                            <input type="number" class="form-control" value="{{ $input['end_stock'] }}" id="end_stock" name="end_stock">
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-secondary">Submit</button>
            </div>
        </form>
    </div>
</div>
<div class="card">
    <div class="card-header bg-white">
        <div class="row justify-content-center justify-content-lg-between align-items-center">
            <div class="col-md-7 col-lg-4 mb-2 mb-lg-0">
                <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal" data-target="#filterModal">
                    <i class="fas fa-filter mr-1"></i>
                    Sortir / Saring
                </button>
            </div>
            <div class="col-lg-auto col-md-5 col-lg-3">
                <form action="{{ route('stocks.index') }}" method="get">
                    <input type="hidden" name="order_by" value="{{ $input['order_by'] }}">
                    <input type="hidden" name="order_direction" value="{{ $input['order_direction'] }}">

                    <input type="hidden" name="start_stock" value="{{ $input['start_stock'] }}">
                    <input type="hidden" name="end_stock" value="{{ $input['end_stock'] }}">
                    <div class="input-group input-group-sm">
                        <input type="search" class="form-control" name="keyword" id="q" placeholder="Pencarian" value="{{ empty($input['keyword']) ? '' : $input['keyword'] }}">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="submit" id="button-addon2">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="card-body" id="printall">
        <div class="table-responsive mb-3">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th class="text-center align-middle" style="width: 2px">No</th>
                        <th class="align-middle">ID Barang</th>
                        <th class="align-middle">Deskripsi</th>
                        <th class="align-middle text-right">Harga</th>
                        <th class="align-middle text-right">Stok</th>
                        <th class="align-middle text-right">Cabang</th>
                        <th class="align-middle text-right">Satuan Barang</th>
                    </tr>
                </thead>
                <tbody>
                    @if (empty($items))
                    <tr>
                        <td class="text-center" colspan="8">
                            Data tidak ditemukan.
                        </td>
                    </tr>
                    @else
                    @foreach ($items as $item)
                    <tr>
                        <td class="text-center align-middle">
                            {{ $number }}
                        </td>
                        <td class="align-middle">
                            {{ $item->part_number }}
                        </td>
                        <td class="align-middle">
                            {{ $item->description }}
                        </td>
                        <td class="align-middle text-right">
                            {{ 'Rp' . currency($item->price) }}
                        </td>

                        <td class="align-middle text-right">
                            {{ $item->stock }}
                        </td>

                        <td class="align-middle text-right">
                            {{ $item->keterangan_cabang }}
                        </td>

                        <td class="align-middle">
                            {{ $item->satuan_brg }}
                        </td>
                    </tr>
                    @php
                    $number++
                    @endphp
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        <div class="row justify-content-end">
            <div class="col-auto">
                <nav>
                    <ul class="pagination pagination-sm">
                        @if ($input['page'] === 1)
                        <li class="page-item disabled">
                            <a href="" class="page-link">
                                << </a>
                        </li>

                        <li class="page-item disabled">
                            <a href="" class="page-link">
                                < </a>
                        </li>

                        <li class="page-item active">
                            <a href="{{ route('stocks.index', ['page' => 1, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword'], 'start_stock' => $input['start_stock'], 'end_stock' => $input['end_stock']]) }}" class="page-link">
                                1
                            </a>
                        </li>

                        @for ($i = 2; $i <= $pageTotal; $i++) @if ($i < 4) <li class="page-item">
                            <a class="page-link" href="{{ route('stocks.index', ['page' => $i, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword'], 'start_stock' => $input['start_stock'], 'end_stock' => $input['end_stock']]) }}">
                                {{ $i }}
                            </a>
                            </li>
                            @endif
                            @endfor

                            <li class="page-item">
                                <a class="page-link" href="{{ route('stocks.index', ['page' => $input['page'] + 1, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword'], 'start_stock' => $input['start_stock'], 'end_stock' => $input['end_stock']]) }}">
                                    >
                                </a>
                            </li>

                            <li class="page-item">
                                <a class="page-link" href="{{ route('stocks.index', ['page' => $pageTotal, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword'], 'start_stock' => $input['start_stock'], 'end_stock' => $input['end_stock']]) }}">
                                    >>
                                </a>
                            </li>
                            @else
                            <li class="page-item">
                                <a class="page-link" href="{{ route('stocks.index', ['page' => 1, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword'], 'start_stock' => $input['start_stock'], 'end_stock' => $input['end_stock']]) }}">
                                    << </a>
                            </li>

                            <li class="page-item">
                                <a class="page-link" href="{{ route('stocks.index', ['page' => $input['page'] - 1, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword'], 'start_stock' => $input['start_stock'], 'end_stock' => $input['end_stock']]) }}">
                                    < </a>
                            </li>

                            @php
                            $pageStartNumber = $input['page'] !== $pageTotal ? $input['page'] - 1 : $input['page'] - 2;
                            $loopingNumberStop = $input['page'] !== $pageTotal ? $input['page'] + 1 : $input['page'];
                            $pageStartNumber = $pageStartNumber < 1 ? 1 : $pageStartNumber; @endphp @for ($i=$pageStartNumber; $i <=$loopingNumberStop; $i++) <li class="page-item {{ $input['page'] === $i ? 'active' : '' }}">
                                <a class="page-link" href="{{ route('stocks.index', ['page' => $i, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword'], 'start_stock' => $input['start_stock'], 'end_stock' => $input['end_stock']]) }}">
                                    {{ $i }}
                                </a>
                                </li>
                                @endfor

                                <li class="page-item {{ $input['page'] === $pageTotal ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ route('stocks.index', ['page' => $input['page'] + 1, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword'], 'start_stock' => $input['start_stock'], 'end_stock' => $input['end_stock']]) }}">
                                        >
                                    </a>
                                </li>

                                <li class="page-item {{ $input['page'] === $pageTotal ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ route('stocks.index', ['page' => $pageTotal, 'order_by' => $input['order_by'], 'order_direction' => $input['order_direction'], 'keyword' => $input['keyword'], 'start_stock' => $input['start_stock'], 'end_stock' => $input['end_stock']]) }}">
                                        >>
                                    </a>
                                </li>
                                @endif
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
<script>
    function printContent() {
        var content = document.getElementById("printall").innerHTML;
        var originalContent = document.body.innerHTML;
        document.body.innerHTML = content;
        window.print();
        document.body.innerHTML = originalContent;
    }
</script>
@endsection