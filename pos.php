<?php

include "includes/header.php";

?>

<!-- Main Content (Scrollable) -->
<main class="col-md-7 d-flex flex-column flex-grow-1 overflow-auto p-3 vh-100">

</main>

<!-- Right Sidebar (Scrollable Sales List) -->
<aside class="col-12 col-md-3 d-flex flex-column border-start p-3 vh-100">
    <h2 class="fw-bold text-center">CURRENT SALE</h2>

    <ul class="list-group overflow-auto flex-grow-1 mb-3">
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <div class="text-truncate w-75">Muasdesadsadsadsadsadffin</div>
            <span class="badge bg-primary rounded-pill">₱80.00</span>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <div class="text-truncate w-75">Muasdesadsadsadsadsadffin</div>
            <span class="badge bg-primary rounded-pill">₱80.00</span>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <div class="text-truncate w-75">Muasdesadsadsadsadsadffin</div>
            <span class="badge bg-primary rounded-pill">₱80.00</span>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <div class="text-truncate w-75">Muasdesadsadsadsadsadffin</div>
            <span class="badge bg-primary rounded-pill">₱80.00</span>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <div class="text-truncate w-75">Muasdesadsadsadsadsadffin</div>
            <span class="badge bg-primary rounded-pill">₱80.00</span>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <div class="text-truncate w-75">Muasdesadsadsadsadsadffin</div>
            <span class="badge bg-primary rounded-pill">₱80.00</span>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <div class="text-truncate w-75">Muasdesadsadsadsadsadffin</div>
            <span class="badge bg-primary rounded-pill">₱80.00</span>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <div class="text-truncate w-75">Muasdesadsadsadsadsadffin</div>
            <span class="badge bg-primary rounded-pill">₱80.00</span>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <div class="text-truncate w-75">Muasdesadsadsadsadsadffin</div>
            <span class="badge bg-primary rounded-pill">₱80.00</span>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <div class="text-truncate w-75">Muasdesadsadsadsadsadffin</div>
            <span class="badge bg-primary rounded-pill">₱80.00</span>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <div class="text-truncate w-75">Muasdesadsadsadsadsadffin</div>
            <span class="badge bg-primary rounded-pill">₱80.00</span>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <div class="text-truncate w-75">Muasdesadsadsadsadsadffin</div>
            <span class="badge bg-primary rounded-pill">₱80.00</span>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <div class="text-truncate w-75">Muasdesadsadsadsadsadffin</div>
            <span class="badge bg-primary rounded-pill">₱80.00</span>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-center">
            <div class="text-truncate w-75">Muasdesadsadsadsadsadffin</div>
            <span class="badge bg-primary rounded-pill">₱80.00</span>
        </li>
        <!-- More items can go here -->
    </ul>

    <div class="d-flex justify-content-between mb-2">
        <h2 class="fw-bold">Total:</h2>
        <h2 class="fw-bold">₱384.00</h2>
    </div>

    <button class="btn btn-success w-100">Complete Sale</button>

    <!-- Simple Calculator -->
    <div class="mt-3 p-3 border rounded bg-light">
        <h6 class="fw-bold text-center">Calculator</h6>
        <input type="text" id="calc-display" class="form-control text-end mb-2" disabled>
        <div class="row g-1">
            <div class="col-3"><button class="btn btn-secondary w-100" onclick="appendToCalc('7')">7</button></div>
            <div class="col-3"><button class="btn btn-secondary w-100" onclick="appendToCalc('8')">8</button></div>
            <div class="col-3"><button class="btn btn-secondary w-100" onclick="appendToCalc('9')">9</button></div>
            <div class="col-3"><button class="btn operator-btn w-100" onclick="appendToCalc('/')">÷</button></div>

            <div class="col-3"><button class="btn btn-secondary w-100" onclick="appendToCalc('4')">4</button></div>
            <div class="col-3"><button class="btn btn-secondary w-100" onclick="appendToCalc('5')">5</button></div>
            <div class="col-3"><button class="btn btn-secondary w-100" onclick="appendToCalc('6')">6</button></div>
            <div class="col-3"><button class="btn operator-btn w-100" onclick="appendToCalc('*')">×</button></div>

            <div class="col-3"><button class="btn btn-secondary w-100" onclick="appendToCalc('1')">1</button></div>
            <div class="col-3"><button class="btn btn-secondary w-100" onclick="appendToCalc('2')">2</button></div>
            <div class="col-3"><button class="btn btn-secondary w-100" onclick="appendToCalc('3')">3</button></div>
            <div class="col-3"><button class="btn operator-btn w-100" onclick="appendToCalc('-')">−</button></div>

            <div class="col-3"><button class="btn btn-danger w-100" onclick="clearCalc()">C</button></div>
            <div class="col-3"><button class="btn btn-secondary w-100" onclick="appendToCalc('0')">0</button></div>
            <div class="col-3"><button class="btn btn-success w-100" onclick="calculateResult()">=</button></div>
            <div class="col-3"><button class="btn operator-btn w-100" onclick="appendToCalc('+')">+</button></div>
        </div>
    </div>
</aside>


</div>
</div>

<!-- Include Bootstrap JS -->
<script>
    document.addEventListener("DOMContentLoaded", function() {

        // Calculator Variables
        let calcDisplay = document.getElementById("calc-display");
        let calcExpression = "";

        if (!calcDisplay) {
            console.error("⚠️ Calculator input field not found! Check your HTML.");
            return;
        }

        // Function to append numbers/operators
        window.appendToCalc = function(value) {
            calcExpression += value;
            calcDisplay.value = calcExpression;
        };

        // Function to clear the display
        window.clearCalc = function() {
            calcExpression = "";
            calcDisplay.value = "";
        };

        // Function to evaluate the expression
        window.calculateResult = function() {
            try {
                calcExpression = eval(calcExpression).toString();
                calcDisplay.value = calcExpression;
            } catch (error) {
                calcDisplay.value = "Error";
                calcExpression = "";
            }
        };
    });
</script>
<?php include "includes/footer.php"; ?>