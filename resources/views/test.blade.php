@extends('layouts.new')

@section('content')
    <ul class="breadcrumb">
        <li>
            <a href="{{ route('home') }}">Dashboard</a>
        </li>
        <li>
            Test
        </li>
    </ul>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="card-title">Test pagina</div>
                <div class="card-tools">
                    <a href="#"><i class="fas fa-edit"></i></a>
                    <a href="#"><i class="fas fas-trash"></i></a>
                </div>
            </div>
            <div class="card-body">
                <button class="btn-success" onclick="binx.modal('testModal1').open();">Modal!</button>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="card-title">Buttons</div>
            </div>
            <div class="card-body">
                <button>Button</button>
                <button class="btn-primary">Primary</button>
                <button class="btn-success">Success</button>
                <button class="btn-danger">Danger</button>
                <button class="btn-warning">Warning</button>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <div class="card-title">Toasts</div>
            </div>
            <div class="card-body">
                <button onclick="binx.toast('Title', 'Body')">Toast</button>
                <button class="btn-primary" onclick="binx.toast('Title', 'Body', 'toast-primary')">Primary</button>
                <button class="btn-success" onclick="binx.toast('Title', 'Body', 'toast-success')">Success</button>
                <button class="btn-danger" onclick="binx.toast('Title', 'Body', 'toast-danger')">Danger</button>
                <button class="btn-warning" onclick="binx.toast('Title', 'Body', 'toast-warning')">Warning</button>
            </div>
        </div>
    </div>

    <div id="testModal1" class="modal">
        <div class="modal-header">
            <div class="modal-title">Modal #1 mattie</div>
            <div class="modal-tools">
                <span onclick="binx.modal(this).close();" class="pointer"><i class="fas fa-times"></i></span>
            </div>
        </div>

        <div class="modal-body">
            Model body friend!
        </div>

        <div class="modal-footer">
            <button class="btn-danger" onclick="binx.modal(this).close()">Close</button>
        </div>
    </div>

    <div id="testModal2" class="modal">
        <div class="modal-header">
            <div class="modal-title">Modal #2 mattie</div>
            <div class="modal-tools">
                <span onclick="binx.modal(this).close();" class="pointer"><i class="fas fa-times"></i></span>
            </div>
        </div>

        <div class="modal-body">
            Model body friend!
        </div>

        <div class="modal-footer">
            Modal footer man!
        </div>
    </div>
@endsection