<div class="a-container">
    <div class="form-box">
        <i class="fas fa-times"></i>

        <div class="progress">
            <!-- <div class="logo">
                <h3>Appointment Form</h3>
            </div> -->
            <ul class="progress-steps">
                <li class="step active">
                    <span>1</span>
                    <p>Personal</p>
                </li>
                <li class="step">
                    <span>2</span>
                    <p>Medical/Nail Condition</p>
                </li>
                <li class="step">
                    <span>3</span>
                    <p>Appointment Information</p>
                </li>
            </ul>
        </div>
        <form action="">
            <div class="form-one form-step active">
                <h5>Personal Information</h5>
                <p>Enter your personal information correctly.</p>
                <div>
                    <label for="full-name">Full Name:</label>
                    <input type="text" name="name" id="full-name" placeholder="e.g. Josep Racelis">
                </div>

                <div>
                    <label for="address">Address:</label>
                    <input type="text" name="address" id="address">
                </div>

                <div>
                    <label for="email">Email Address:</label>
                    <input type="text" name="email" id="email">
                </div>

                <div>
                    <label for="mnumber">Mobile Number:</label>
                    <input type="tel" name="mnumber" id="mnumber" placeholder="(+63) 9xx xxxx xxx">
                </div>

                <div>
                    <label for="payment">Payment Method</label>
                    <select name="payment" id="payment">
                        <option value="Soft-gel Extension">GCASH</option>
                    </select>
                </div>
            </div>

            <div class="form-two form-step">
                <h5>Medical/Nail Condition</h5>
                <div>
                    <p>Is this your first time manicure/pedicure ?</p>
                    <div class="radio-div">
                        <div>
                            <input type="radio" name="isFirsttime" id="isFirsttimeYes" value="yes">
                            <label for="isFirsttimeYes">Yes</label>
                        </div>
                        <div>
                            <input type="radio" name="isFirsttime" id="isFirsttimeNo" value="no">
                            <label for="isFirsttimeNo">No</label>
                        </div>
                    </div>
                </div>

                <div>
                    <p>Have you ever experience allergic reaction or irritation from any type of nail or skin
                        product?</p>
                    <div class="radio-div">
                        <div>
                            <input type="radio" name="allergicText" id="allergicYes" value="yes">
                            <label for="allergicYes">Yes</label>
                        </div>
                        <div>
                            <input type="radio" name="allergicText" id="allergicNo" value="no">
                            <label for="allergicNo">No</label>
                        </div>
                    </div>
                    <div class='specify-div'>
                        <p>If yes, please specify:</p>
                        <input type="text" id="allergicSpecify">
                    </div>

                </div>

                <div>
                    <p>Do you take part in any hands-on hobbies or sports activities</p>
                    <div class="radio-div">
                        <div>
                            <input type="radio" name="sport" id="sportYes" value="yes">
                            <label for="sportYes">Yes</label>
                        </div>
                        <div>
                            <input type="radio" name="sport" id="sportNo" value="no">
                            <label for="sportNo">No</label>
                        </div>
                    </div>
                    <div class='specify-div'>
                        <p>If yes, please specify:</p>
                        <input type="text" id="sportSpecify">
                    </div>

                </div>

                <div>
                    <p>Please check any of the following medical or skin condition</p>
                    <div class="m-conditions-container">
                        <div class="checkbox-div">
                            <input type="checkbox" name="condition[]" id="allergies" value="Allergies">
                            <label for="allergies">Allergies</label>
                        </div>
                        <div class="checkbox-div">
                            <input type="checkbox" name="condition[]" id="diabetes" value="Diabetes">
                            <label for="diabetes">Diabetes</label>
                        </div>
                        <div class="checkbox-div">
                            <input type="checkbox" name="condition[]" id="skin-irritation" value="Skin Irritation">
                            <label for="skin-irritation">Skin Irritation</label>
                        </div>
                        <div class="checkbox-div">
                            <input type="checkbox" name="condition[]" id="skin-inflammation" value="Skin Inflammation">
                            <label for="skin-inflammation">Skin Inflammation</label>
                        </div>
                        <div class="checkbox-div">
                            <input type="checkbox" name="condition[]" id="arthritis" value="Arthritis">
                            <label for="arthritis">Arthritis</label>
                        </div>


                        <div class="checkbox-div">
                            <input type="checkbox" name="condition[]" id="bb-disease" value="Blood Born Disease">
                            <label for="bb-disease">Blood Born Disease</label>
                        </div>
                        <div class="checkbox-div">
                            <input type="checkbox" name="condition[]" id="b-skin" value="Broken Skin">
                            <label for="b-skin">Broken Skin</label>
                        </div>
                        <div class="checkbox-div">
                            <input type="checkbox" name="condition[]" id="Hemophilia" value="Hemophilia">
                            <label for="Hemophilia">Hemophilia</label>
                        </div>
                        <div class="checkbox-div">
                            <input type="checkbox" name="condition[]" id="r-surgery" value="Recent Surgery">
                            <label for="r-surgery">Recent Surgery</label>
                        </div>
                        <div class="checkbox-div">
                            <input type="checkbox" name="condition[]" id="Swelling" value="Swelling">
                            <label for="Swelling">Swelling</label>
                        </div>


                        <div class="checkbox-div">
                            <input type="checkbox" name="condition[]" id="a-foot" value="Athletes Foot">
                            <label for="a-foot">Athletes Foot</label>
                        </div>
                        <div class="checkbox-div">
                            <input type="checkbox" name="condition[]" id="Calluses" value="Calluses">
                            <label for="Calluses">Calluses</label>
                        </div>
                        <div class="checkbox-div">
                            <input type="checkbox" name="condition[]" id="n-infection" value="Nail Infection">
                            <label for="n-infection">Nail Infection</label>
                        </div>
                        <div class="checkbox-div">
                            <input type="checkbox" name="condition[]" id="Corns" value="Corns">
                            <label for="arthritis">Corns</label>
                        </div>
                    </div>
                </div>

                <div>
                    <p>How would you describe the typical condition of your nail?</p>
                    <div class="n-conditions-container">
                        <div class="checkbox-div">
                            <input type="checkbox" name="nail-condition[]" id="Soft" value="Soft">
                            <label for="Soft">Soft</label>
                        </div>
                        <div class="checkbox-div">
                            <input type="checkbox" name="nail-condition[]" id="Hard" value="Hard">
                            <label for="Hard">Hard</label>
                        </div>
                        <div class="checkbox-div">
                            <input type="checkbox" name="nail-condition[]" id="Bendy" value="Bendy">
                            <label for="Bendy">Bendy</label>
                        </div>

                        <div class="checkbox-div">
                            <input type="checkbox" name="nail-condition[]" id="Flakey" value="Flakey">
                            <label for="Flakey">Flakey</label>
                        </div>
                        <div class="checkbox-div">
                            <input type="checkbox" name="nail-condition[]" id="Brittle" value="Brittle">
                            <label for="Brittle">Brittle</label>
                        </div>
                        <div class="checkbox-div">
                            <input type="checkbox" name="nail-condition[]" id="SnapEasily" value="Snap Easily">
                            <label for="SnapEasily">Snap Easily</label>
                        </div>

                        <div class="checkbox-div">
                            <input type="checkbox" name="nail-condition[]" id="SplitEasily" value="Split Easily">
                            <label for="SplitEasily">Split Easily</label>
                        </div>
                        <div class="checkbox-div">
                            <input type="checkbox" name="nail-condition[]" id="Normal" value="Normal">
                            <label for="Normal">Normal</label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-three form-step ">
                <h5>Appointment Information</h5>
                <div class='service-div'>
                    <label for="service">Choose a service:</label>

                    <select name="service" id="service">
                        <option value="Soft-gel Extension">Soft Gel Extension</option>
                        <option value="Soft-gel Removal">Soft Gel Removal</option>
                    </select>
                </div>
                <div>
                    <label for="">Appointment Date:</label>
                    <div class="calendar-btn-div">
                        <button id='select-date-btn' type="button">Select a date</button>
                        <?php include("calendar.php") ?>
                        <input type="hidden" name="appointment-date" value="" id="date-picker" onchange="console.log('hi')">
                        <p class="error-msgg">Appointment date is required</p>
                    </div>
                </div>

                <div class="time-radio-div">
                    <label for="">Time:</label>

                    <div id="timeRadioButtons">
                        <!-- Time radio buttons will be populated here dynamically -->
                        <!-- <div class="time-valid">
                            <input type="radio" name="appointment-time" value="10:00 AM - 11:00 AM" id="1">
                            <label for="1">10:00 AM - 11:00 AM</label>
                        </div>
                        <div class="time-invalid">
                            <input type="radio" name="appointment-time" value="10:00 AM - 11:00 AM" id="1">
                            <label for="1">01:00 PM - 02:00 PM</label>
                        </div> -->
                    </div>


                    <p class="error-msgg">Appointment time is required.</p>

                </div>
            </div>

            <div class="btn-group">
                <button type="button" class="btn-prev" disabled>Back</button>
                <button type="button" class="btn-next">Next Step</button>
                <button type="submit" class="btn-submit">Submit</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="sweetalert2.all.min.js"></script>