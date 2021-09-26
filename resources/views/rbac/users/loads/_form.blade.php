{!! Form::model($user, array('id' => 'user_form','class' => 'form-horizontal', 'method' => $method)) !!}
{!! Form::hidden('user_id', $user->id,['id'=>'user_id']) !!}
<div class="form-group">
    {!! Form::label('name','* Nombre:', array('class' => 'control-label col-md-3')) !!}
    <div class="col-md-9">
        {!! Form::text('name', $user->name, array('class' => 'form-control', 'autocomplete' =>
        'off', 'placeholder' => 'ej. Operador', 'maxlength' => '64')) !!}
    </div>
</div>
<div class="form-group">
    {!! Form::label('name','* Email:', array('class' => 'control-label col-md-3')) !!}
    <div class="col-md-9">
        {!! Form::text('email', $user->email, array('class' => 'form-control', 'autocomplete' =>
        'off','id'=>'email', 'placeholder' => 'ej. operador@lovekiss.me', 'maxlength' => '64')) !!}
    </div>
</div>


<div class="form-group">
    {!! Form::label('name','* Roles:', array('class' => 'control-label col-md-3')) !!}
    <div class="col-md-9">
        {!! Form::select('role[]',$roles, $user->roles()->pluck('id')->toArray(), array('class' => 'form-control', 'id'=>'role','multiple'=>'multiple' )) !!}
    </div>
</div>
{{-- <div class="form-group">
    {!! Form::label('provider','*Emprendedor:', array('class' => 'control-label  col-md-3')) !!}
    <div class="col-md-9">
        {!! Form::select('provider_id', [''=>'--Seleccione--'] + $providers, $user->provider_id, ['class' => 'form-control', 'id'=>'provider_id'] ) !!}
    </div>
</div> --}}
@if ($user->id)
    <details>
        <summary>Cambiar contraseña</summary>
        <div class="form-group">
            {!! Form::label('name','* Contraseña:', array('class' => 'control-label col-md-3')) !!}
            <div class="col-md-9">
                {!! Form::password('password', array('class' => 'form-control', "id"=>'password', 'autocomplete' =>
                'off', 'maxlength' => '64')) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('name','* Confirmar Contraseña:', array('class' => 'control-label col-md-3')) !!}
            <div class="col-md-9">
                {!! Form::password('confirm_password',  array('class' => 'form-control', 'autocomplete' =>
                'off', "id"=>'confirm_password',)) !!}
            </div>
        </div>
    </details>
    <br>
@else
    <div class="form-group">
        {!! Form::label('name','* Contraseña:', array('class' => 'control-label col-md-3')) !!}
        <div class="col-md-9">
            {!! Form::password('password', array('class' => 'form-control', "id"=>'password', 'autocomplete' =>
            'off', 'maxlength' => '64')) !!}
        </div>
    </div>
    <div class="form-group">
        {!! Form::label('name','* Confirmar Contraseña:', array('class' => 'control-label col-md-3')) !!}
        <div class="col-md-9">
            {!! Form::password('confirm_password',  array('class' => 'form-control', 'autocomplete' =>
            'off', "id"=>'confirm_password',)) !!}
        </div>
    </div>
@endif
@if ($user->id)
    <div class="form-group">
        {!! Form::label('status','* Estado:', array('class' => 'control-label col-md-3')) !!}
        <div class="col-md-9">
            {!! Form::select('status', array( 'ACTIVE' => 'Activo', 'INACTIVE' => 'Inactivo'),$user->status,array('class' => 'form-control') ) !!}
        </div>
    </div>
@endif
{!! Form::close() !!}