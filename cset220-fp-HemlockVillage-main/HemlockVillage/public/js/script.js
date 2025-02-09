function submitHomePageWithDate(event)
{
	event.preventDefault();

	const dateInput = document.querySelector("#date")

	if (!dateInput)
	{
		console.error("date input field is missing")
		return
	}

	// Redirect back to /home if no date inputted
	if (!dateInput.value)
		top.location = `/home`

	else
		top.location = `/home/${dateInput.value}`
}
