$(document).ready(function() {

    /* Generate SEO By AI */
    $('#GenerateSEOByAiBtn').click(function(){
        $('#GenerateSEOByAiBtn').attr('disabled', 'true');
        $('#GenerateSEOByAiBtn i').removeClass('fa-magic');
        $('#GenerateSEOByAiBtn i').addClass('fa-spinner fa-spin');
        let post_url = $('#GenerateSEOByAiBtn').data('post-url');
        let page = $('#GenerateSEOByAiBtn').data('page');
        let page_id = $('#GenerateSEOByAiBtn').data('page-id');
        
        $.post(post_url, {page: page,page_id:page_id}, function(data) {
            
            var result = JSON.parse(data);
            //console.log(result);
            
            if (result.status === 'success' && result.seo_by_lang != undefined) {
                 var seo_by_lang = result.seo_by_lang;
               
                if(result.seo_fields_mode == 'with_lang'){ //seo fields with lang ext as title_en
                    Object.entries(seo_by_lang).forEach(([lang, item]) => {
                        $('input[name=title_'+lang+"]").val(item.title);
                        $('input[name=description_'+lang+"]").val(item.description);
                        $('input[name=keywords_'+lang+"]").val(  
                            item.keywords.map(function(k) {
                                return ',' + k;
                            }).join('')
                        );
                        
                    });
                }else{ //seo fields without lang ext as title
                    Object.entries(seo_by_lang).forEach(([lang, item]) => {
                        $('input[name=title]').val(item.title);
                        $('input[name=description]').val(item.description);
                        $('input[name=keywords]').val(  
                            item.keywords.map(function(k) {
                                return ',' + k;
                            }).join('')
                        );
                    });
                }
                
                notify('Success',result.message,'success');
            } else {
                notify('Error',result.message,'error');
            }

            $('#GenerateSEOByAiBtn').removeAttr('disabled');
            $('#GenerateSEOByAiBtn i').removeClass('fa-spinner fa-spin');
            $('#GenerateSEOByAiBtn i').addClass('fa-magic');
        });
    });
    

});

/************* Generate Module item Content By AI ******************/
   $('#AddDataByAI').click(function() {
        $('#AddDataByAIModal').modal();
    });
    

     $('#YesAddByAI').click(function(){
        $('#YesAddByAI').attr('disabled', 'true');
        $('#YesAddByAI i').removeClass('fa-magic');
        $('#YesAddByAI i').addClass('fa-spinner fa-spin');
        var item_topic_area = document.getElementById("item_topic");
        item_topic_area.style.border = "";
        $('#error-msg').addClass('hidden');

        let post_url = $('#YesAddByAI').data('post-url');
        let module_id = $('#YesAddByAI').data('module_id');
        let return_url = $('#YesAddByAI').data('return-url');

        
        let item_topic = item_topic_area.value;
        if(item_topic != ''){
            //generate_Module_Item_Content_By_Ai
            $.post(post_url, {module_id: module_id,item_topic:item_topic}, function(data) {
                var result = JSON.parse(data);
                //console.log(result);
                
                
                if (result.status === 'success' && result.item_inserted_id != undefined) {
                    let module_path= result.module_path; 
                    let inserted_id = result.item_inserted_id;
                     notify('Success',result.message,'success');
                    setTimeout(() => {
                        
                        $('#item_topic').val('');
                        $('#AddModuleItemByAIModal').hide();

                        location.href = return_url+"/edit/"+inserted_id+"?return_url="+return_url
                    }, 300);
                } else {
                     notify('Error',result.message,'error');
                }

                $('#YesAddByAI').removeAttr('disabled');
                $('#YesAddByAI i').removeClass('fa-spinner fa-spin');
                $('#YesAddByAI i').addClass('fa-magic');
            });
        }else{
            item_topic_area.style.border = "1px solid red";
             $('#error-msg').removeClass('hidden');
             
             $('#YesAddByAI').removeAttr('disabled');
             $('#YesAddByAI i').removeClass('fa-spinner fa-spin');
             $('#YesAddByAI i').addClass('fa-magic');
            return false;
        }
        
    });


    // Prevent dropdown from closing when clicking inside it
    $('.dropdown-ai-actions .dropdown-menu').on('click', function (e) {
        e.stopPropagation();
    });
    

    $('.improve-content-cls').click(function(){
        var col = $(this).data('col');
        var type = $(this).data('type');
        let language = $(this).data('language');
        let module_id = $(this).data('module_id');
        let post_url = $(this).data('post-url');

        $('.improve-content-cls[data-col='+col+'] i').removeClass('fa-edit');
        $('.improve-content-cls[data-col='+col+'] i').addClass('fa-spinner fa-spin');

        var content = $("[name="+col+"]").val();
        //console.log(content);

        if(content != ''){
            $.post(post_url, {content:content, language: language, module_id:module_id,field_name:col}, function(data) {
                var result = JSON.parse(data);
                //console.log(result);
                
                if (result.status === 'success' && result.improved_content != undefined) {
                    let improved_content = result.improved_content;
                     notify('Success',result.message,'success');
                    //console.log(improved_content);
                    if(type == 'wysiwyg'){ //field as tinyMce textarea
                        tinymce.get('textarea_'+col).setContent(improved_content);
                    }else{ //type as text
                        $("[name="+col+"]").val(improved_content);
                    }
                   
                } else {
                    notify('Error',result.message,'error');
                }
               
                $('.improve-content-cls[data-col='+col+'] i').removeClass('fa-spinner fa-spin');
                $('.improve-content-cls[data-col='+col+'] i').addClass('fa-edit');
            });
        }else{
            
               notify('Warning','No content in this field.','warning');
              $('.improve-content-cls[data-col='+col+'] i').removeClass('fa-spinner fa-spin');
              $('.improve-content-cls[data-col='+col+'] i').addClass('fa-edit');
            return false;
        }
        
    });

    $('.translate-content-cls').click(function(){
        var col = $(this).data('col');
        var type = $(this).data('type');
        let source_lang = $(this).data('source_lang');
        let target_lang = $(this).data('target_lang');
        let post_url = $(this).data('post-url');
        
        var target_col = col;
        var source_col = col.replace('_'+target_lang, '_'+source_lang);

        $('.translate-content-cls[data-col='+target_col+'][data-source_lang="'+source_lang+'"] i').removeClass('fa-language');
        $('.translate-content-cls[data-col='+target_col+'][data-source_lang="'+source_lang+'"] i').addClass('fa-spinner fa-spin');

        
        var content = $("[name="+source_col+"]").val();
        //console.log(content);
        //alert(content);

        if(content != ''){
            $.post(post_url, {content:content, source_lang: source_lang, target_lang:target_lang}, function(data) {
                var result = JSON.parse(data);
                //console.log(result);
                
                if (result.status === 'success' && result.translated_content != undefined) {
                    let translated_content = result.translated_content;
                    notify('Success',result.message,'success');
                    //console.log(improved_content);
                    if(type == 'wysiwyg'){ //field as tinyMce textarea
                        tinymce.get('textarea_'+target_col).setContent(translated_content);
                    }else{ //type as text
                       $("[name="+target_col+"]").val(translated_content);
                    }
                    
                } else {
                     notify('Error',result.message,'error');
                }
                
                $('.translate-content-cls[data-col='+target_col+'][data-source_lang="'+source_lang+'"] i').removeClass('fa-spinner fa-spin');
                $('.translate-content-cls[data-col='+target_col+'][data-source_lang="'+source_lang+'"] i').addClass('fa-language');
            });
        }else{
                notify('Warning','No content in source field.','warning');
                $('.translate-content-cls[data-col='+target_col+'][data-source_lang="'+source_lang+'"] i').removeClass('fa-spinner fa-spin');
                $('.translate-content-cls[data-col='+target_col+'][data-source_lang="'+source_lang+'"] i').addClass('fa-language');
            return false;
        }
    
    });