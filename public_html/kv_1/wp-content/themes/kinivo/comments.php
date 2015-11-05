<?php if ( have_comments() ) : ?>
    <h3 id="comments"><?php comments_number('0 <span>Comments</span>', '1 <span>Comment</span>', '% <span>Comments</span>');?></h3>
        <div class="navigation">
            <div class="alignleft"><?php previous_comments_link() ?></div>
            <div class="alignright"><?php next_comments_link() ?></div>
        </div>
    <ol class="commentlist">
     <?php
     wp_list_comments(array(
      // 'login_text'        => 'Login to reply',
      // 'callback'          => null,
      // 'end-callback'      => null,
      // 'type'              => 'all',
      // 'avatar_size'       => 32,
      // 'reverse_top_level' => null,
      // 'reverse_children'  =>
      ));
      ?>
    </ol>
        <div class="navigation">
            <div class="alignleft"><?php previous_comments_link() ?></div>
            <div class="alignright"><?php next_comments_link() ?></div>
        </div>
    <?php
    if ( ! comments_open() ) : // There are comments but comments are now closed
        echo"<p class='nocomments'>Comments are closed.</p>";
    endif;
 
else : // I.E. There are no Comments
    if ( comments_open() ) : // Comments are open, but there are none yet
        // echo"<p>Be the first to write a comment.</p>";
    else : // comments are closed
        echo"<p class='nocomments'>Comments are closed.</p>";
    endif;
endif;
?>





<?php 
$args = array(
  'id_form'           => 'commentform',
  'id_submit'         => 'submit',
  'title_reply'       => __( '' ),
  'title_reply_to'    => __( 'Leave a Reply to %s' ),
  'cancel_reply_link' => __( 'Cancel Reply' ),
  'label_submit'      => __( 'Post' ),

  'comment_field' =>  
      '<fieldset class="total">
        <label for="">Write your post</label>
        <textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea>
        <div id="responseContact"></div>
      </fieldset>',

  'must_log_in' => '',

  'logged_in_as' => '',

  'comment_notes_before' => '',

  'comment_notes_after' => '',

  'fields' => apply_filters( 'comment_form_default_fields', array(

    'author' =>
      '<fieldset class="in-line">
        <label for="">Name</label>
        <input id="author" name="author" type="text" size="30" />
      </fieldset>',

    'email' =>
      '<fieldset class="in-line">
        <label for="">Email Address</label>
        <input id="email" name="email" type="text" size="30" />
      </fieldset>',

    'url' =>''
    )
  ),
);


 

?>


<?php comment_form($args); ?>

<script type="text/javascript">
  jQuery('document').ready(function($){
    // Get the comment form
    var commentform=$('#commentform');
    // Add a Comment Status message
    commentform.prepend('<div id="comment-status" ></div>');
    // Defining the Status message element 
    var statusdiv=$('#comment-status');
    commentform.submit(function(){
      // Serialize and store form data
      var formdata=commentform.serialize();
      //Add a status message
      statusdiv.html('<p>Enviando Comentario...</p>');
      //Bloqueando boton Submit
      commentform.find('.form-submit #submit').attr("disabled", "true");
      //Extract action URL from commentform
      var formurl=commentform.attr('action');
      //Post Form with data
      $.ajax({
        type: 'post',
        url: formurl,
        data: formdata,
        error: function(XMLHttpRequest, textStatus, errorThrown){
          commentform.find('.form-submit #submit').removeAttr("disabled");
          textArea = commentform.find('textarea[name=comment]').val();
          if( textArea.trim() == ''){
            statusdiv.html('<p class="ajax-error" >Llena todos los campos. </p>');
            commentform.find('textarea[name=comment]').val(textArea.trim());
          }else{
            statusdiv.html('<p class="ajax-error" >Ya has comentado esto antes. </p>');
          }

        },
        success: function(data, textStatus){
          statusdiv.html('<p class="ajax-success" >Gracias por comentar.</p>');
          commentform.find('textarea[name=comment]').val('');
          commentform.find('.form-submit #submit').attr("disabled", "true");
          window.location.reload();
        }
      });
      return false;
    });
  });
</script>



