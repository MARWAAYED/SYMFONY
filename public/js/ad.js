$('#add-image').click(function(){

    const index = $('#widgets-counter').val();
    //console.log(index);
    const tmpl = $('#ad_images').data('prototype').replace(/__name__/g, index);
    
    //console.log(tmpl);
    $('#ad_images').append(tmpl);

    $('#widgets-counter').val(index + 1);

    handleDeleteButtons();

function handleDeleteButtons() {
    $('button[data-action="delete"]').click(function(){
        const target = this.dataset.target;
        //console.log(target);
        $(target).remove();
    });
}

handleDeleteButtons();
});