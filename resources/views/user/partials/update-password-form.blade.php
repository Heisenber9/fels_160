{!! Form::open(['route' => 'update_password', 'method' => 'POST', 'class' => 'form-horizontal']) !!}

{!! Form::label('old_password', trans('passwords.old_password')) !!}
{!! Form::password('old_password', ['class' => 'form-control']) !!}

{!! Form::label('password', trans('passwords.new_password')) !!}
{!! Form::password('password', ['class' => 'form-control']) !!}

{!! Form::label('password_confirmation', trans('passwords.password_confirm_label')) !!}
{!! Form::password('password_confirmation', ['class' => 'form-control']) !!}

<br>

{!! Form::button('<i class="fa fa-pencil-square"></i> ' . trans('passwords.change_password_button'), ['type' => 'submit', 'class' => 'btn btn-success']) !!}

{!! Form::close() !!}
