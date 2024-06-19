// Lead Booker Form

jQuery( "#lead_form_submit" ).on( "click", function(event) {
    event.preventDefault();
    const submitLead = async () => {
        const leadBookerForm = document.querySelector("#lead_booker_form");
        const formData = new FormData(leadBookerForm);
        formData.append( 'action', 'submit_lead_data_action' );
        var modal = jQuery('#modalWindow');
    
        try {
            const responseJSON = await fetch(variables.ajaxurl, {
            method: "POST",
            // Set the FormData instance as the request body
            body: formData,
            });
            const response = await responseJSON.json()
            modal.hide();
            alert(response)
        } catch (e) {
            console.error(e);
        }
    }

    submitLead();
});

