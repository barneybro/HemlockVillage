<html>
    <head>
        <title>Payments</title>

        <link rel="stylesheet" href="{{ asset("./css/mainstyle.css") }}">
        <link rel="stylesheet" href="{{ asset("./css/style.css") }}">


        <script src="{{ asset("./js/navigator.js") }}"></script>
        <script src="{{ asset("./js/payment.js") }}"></script>
    </head>

    <body>
        <div class="container">
            <h1>Payments</h1>

            @php
                $accessLevel = Auth::user()->role->access_level;
            @endphp

            {{-- Success --}}
            @if (session('message'))
                <div>
                    {{ session('message') }}
                </div>
            @endif

            <form
                id="payment_form"

                {{-- For admin --}}
                @if(isset($accessLevel) && $accessLevel == 1 && isset($patientId))
                    action="/payment/{{ $patientId }}"
                    method="post"
                @endif
            >
                {{-- Add token if admin --}}
                @if(isset($accessLevel) && $accessLevel == 1 && isset($patientId))
                    @csrf
                    @method("patch")
                @endif

                <div class="form-group">
                    <label for="patient-id">Patient ID:</label>
                    <input type="text" name="patient_id" placeholder="Patient ID" id="patient-id"
                        @isset($patientId) value="{{ $patientId }}" @endisset
                    >

                    {{-- Error GET --}}
                    @isset($error)
                        <div>{{ $error }}</div>
                    @endisset

                    {{-- Error Patient Id PATCH  --}}
                    @error("patient_id") <div>{{ $message }}</div> @enderror
                </div>

                @isset($bill)
                    <div class="form-group">
                        <label for="total-due">Total Due:</label>
                        <p>{{ $bill }}</p>
                    </div>

                    @if(isset($accessLevel) && $accessLevel == 1 && isset($patientId))
                        <div class="form-group">
                            <label for="new-payment">New Payment:</label>
                            <input type="number" id="new-payment"
                                name="amount"
                                placeholder="{{ number_format($bill, 2) }}"
                                min="0"
                                max="{{ $bill }}"
                                step="1"
                            >
                        </div>
                        {{-- Error Amount  PATCH --}}
                        @error("amount") <div>{{ $message }}</div> @enderror

                        <button type="button" id="pay_btn">Pay</button>

                        {{-- Confirmation Modal --}}
                        <div class="modal">
                            <div class="modal_content">
                                <span class="close" id="close_modal">&times;</span>

                                <h2>Are you sure you want to proceed with the payment?</h2>

                                <div class="modal_btn_container">
                                    <button id="confirm_btn">Confirm</button>
                                    <button id="cancel_btn">Cancel</button>
                                </div>
                            </div>
                        </div>
                    @endif
                @endisset
            </form>
        </div>

        @include('navbar')

    </body>
</html>
