function checkIdInput(event)
{
	event.preventDefault()

	if (patientIdInput.value.length < 16) return // Don't do anything until the fixed length of patient id is reached

	setTop(`/payment/${patientIdInput.value}`) // Change the url
}

document.addEventListener("DOMContentLoaded", () =>
{
	patientIdInput = document.querySelector("#patient-id")
	if (!patientIdInput) console.error("none")

	patientIdInput.oninput = () => checkIdInput(event)


	/**
	 *
	 * Modal
	 *
	 */
	const modal = document.querySelector(".modal")
	const payButton = document.querySelector("#pay_btn")
	const confirmButton = document.querySelector("#confirm_btn")
	const cancelButton = document.querySelector("#cancel_btn")
	const closeModal = document.querySelector("#close_modal")
	const form = document.querySelector("#payment_form")

	// TODO validate that the elements exist

	/**
	 * Display
	 */
	payButton.onclick = () =>
	{
		modal.classList.add("show")
	}

	cancelButton.onclick = () =>
	{
		event.preventDefault()
		modal.classList.remove("show")
	}

	closeModal.onclick = () =>
	{
		modal.classList.remove("show")
	}

	// Clicking outside of modal
	window.onclick = (event) =>
	{
		if (event.target == modal)
			modal.classList.remove("show")
	}

	/**
	 * Form submission
	 */
	confirmButton.onclick = () =>
	{
		console.log("coonfirm cancel")

		modal.classList.remove("show")

		form.submit()
	}
})
