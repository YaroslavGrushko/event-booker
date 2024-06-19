// Lead Booker Form
const leadBookerForm = document.querySelector("#lead_booker_form");
var modal = jQuery('#modalWindow');

jQuery( "#lead_form_submit" ).on( "click", function(event) {
    event.preventDefault();
    submitLead();
});

const  submitLead = async () => {
    const formData = new FormData(leadBookerForm);
    formData.append( 'action', 'submit_lead_data_action' );
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