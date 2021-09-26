{!! Form::model($question, array('id' => 'question_form','class' => 'form-horizontal', 'method' => $method)) !!}
{!! Form::hidden('question_id', $question->id, ['id'=>'question_id']) !!}
{!! Form::hidden('plan_id', $plans->id, ['id'=>'plan_id']) !!}

        <div class="form-group ">
            {!! Form::label('title','* Título:', array('class' => 'control-label col-md-3')) !!}
            <div class="col-md-12">
                {!! Form::text('title', $question->title, array('class' => 'form-control', 'autocomplete' =>
                'off','id'=>'title', 'placeholder' => 'ej. ¿Necesitas ayuda?', 'maxlength' => '64')) !!}
            </div>
        </div>
    
       
        
        <div class="row">
            
            <div class="form-group col-6">
                {!! Form::label('weight','* Posición:', array('class' => 'control-label col-md-6')) !!}
                <div class="col-md-6">
                    {!! Form::number('weight', $question->weight, array('class' => 'form-control', 'autocomplete' =>
                    'off','id'=>'title', 'placeholder' => 'ej. 1,2,3', 'min' => '0')) !!}
                </div>
            </div>

            <div class="form-group col-6">
                {!! Form::label('status','* Estado:', array('class' => 'control-label col-md-6')) !!}
                <div class="col-md-6">
                    {!! Form::select('status', array( 'ACTIVE' => 'Activo', 'INACTIVE' => 'Inactivo'),$question->status,array('class' => 'form-control') ) !!}
                </div>
            </div> 
        </div>
  
    <div class="form-group">
        {!! Form::label('description','* Descripción:', array('class' => 'control-label col-md-3')) !!}
        <div class="col-md-12">
        {!! Form::textarea('description', $question->description, array('class' => 'form-control', 'autocomplete' => 'off', 'placeholder' => 'ej. ', 'id'=>'description', 'rows'=> 5)) !!}
    </div>   
    </div>
    
    

{!! Form::close() !!}
<script>
      CKEDITOR.replace('description', {
          extraPlugins: 'colorbutton,colordialog'
      });
</script>

