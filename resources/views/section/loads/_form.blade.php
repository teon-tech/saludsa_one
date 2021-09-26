{!! Form::model($section, array('id' => 'section_form','class' => 'form-horizontal', 'method' => $method)) !!}
{!! Form::hidden('section_id', $section->id, ['id'=>'section_id']) !!}
{!! Form::hidden('plan_id', $plans->id, ['id'=>'plan_id']) !!}
{!! Form::hidden('count', '', ['id' => 'count']) !!}
<div class="row">
    <div class="col-6">
        <div class="form-group ">
            {!! Form::label('title','* Título:', array('class' => 'control-label col-md-3')) !!}
            <div class="col-md-9">
                {!! Form::text('title', $section->title, array('class' => 'form-control', 'autocomplete' =>
                'off','id'=>'title', 'placeholder' => 'ej. Gastos médicos', 'maxlength' => '64')) !!}
            </div>
        </div>
        <div class="form-group ">
            {!! Form::label('weight','* Posición:', array('class' => 'control-label col-md-3')) !!}
            <div class="col-md-9">
                {!! Form::number('weight', $section->weight, array('class' => 'form-control', 'autocomplete' =>
                'off','id'=>'title', 'placeholder' => 'ej. 1,2,3', 'min' => '0')) !!}
            </div>
        </div>
        <div class="form-group">
            {!! Form::label('status','* Estado:', array('class' => 'control-label col-md-6')) !!}
            <div class="col-md-9">
                {!! Form::select('status', array( 'ACTIVE' => 'Activo', 'INACTIVE' => 'Inactivo'),$section->status,array('class' => 'form-control') ) !!}
            </div>
        </div>

    <div class="form-group">
        {!! Form::label('description','* Descripción:', array('class' => 'control-label col-md-3')) !!}
        <div class="col-md-12">
        {!! Form::textarea('description', $section->description, array('class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'ej. ', 'id'=>'description', 'rows'=> 5)) !!}
    </div>
    </div>
    </div>
    <div class="col-6">
        <div class="form-group">
    {!! Form::label('url','* Ingresa una url:', array('class' => 'control-label col-md-9')) !!}
    <div class="col-md-12">
        {!! Form::text('url', '', array('id' => 'url','class' => 'form-control', 'autocomplete' =>
        'off', 'placeholder' => 'ej. http://youtube.com', 'maxlength' => '256', 'title' => 'URL no válida', 'pattern'=> '^https?:\/\/[\w\-]+(\.[\w\-]+)+[/#?]?.*$')) !!}
    </div>
</div>
<button type="button" onclick="addUrl()" class="btn btn-secondary mr-2">Agregar</button>
<br>
<br>
@if ($plans->id)
<table id="url_table" class="table table-bordered ">
    <thead>
    <tr>
      <th style="width:60px"></th>
      <th>Url</th>
    </tr>
    </thead>

        @foreach ($videosSection as $index => $video)
        <tr>
            <td><input type="button" class= "btn btn-danger btn-sm" value="x" onclick="delRow(this)"></td>
            <td>
                <a target="_blank" href="{{$video->url}}">{{$video->url}}</a>
                <input type="hidden" value="{{$video->url}}" name="url_element[{{$index}}]">
            </td>
         </tr>
        @endforeach
  </table>
@else
<table id="url_table" class="table table-bordered ">
    <thead>
    <tr>
      <th style="width:60px"></th>
      <th>Url</th>
    </tr>
    </thead>
    <tbody>
   </tbody>
  </table>
@endif
    </div>
</div>


{!! Form::close() !!}
<script>
    CKEDITOR.replace('description', {
        extraPlugins: 'colorbutton,colordialog'
    });
</script>

