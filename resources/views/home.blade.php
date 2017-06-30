@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">

                    <input type="hidden" name="__token" class="__token" value="{{ $token }}">
                    
                    <div class="input-group" id="add-form">
                        <input type="text" class="form-control newTitle" placeholder="Add Task...">
                        <span class="input-group-btn">
                            <button class="btn btn-default btnAdd" type="button">Add</button>
                        </span>
                    </div>
                    <hr/>
                    <div class="tasklist" id="tasklist">
                       <ul id="tasklist">
                            
                        </ul>
                    </div>

                    <hr/>
                    <p><b>Drag</b> to change the order</p>
                    <textarea id="output"></textarea>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
