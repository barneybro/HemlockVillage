function redirectToSchedule()
{
	const date = document.querySelector("#appointment-date")
	const patientId = document.querySelector("#patient-id")

	// Input fields not found
	if (!date || !patientId)
	{
		console.error("Input fields are missing")
		return
	}

	// Do nothing if one field not filled out yet
	if (!date.value || patientId.value.length < 16) return

	top.location = `/schedule?patient_id=${patientId.value}&appointment_date=${date.value}`
}
