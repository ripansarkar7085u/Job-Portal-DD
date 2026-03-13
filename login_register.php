<div class="modal fade" id="authModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content auth-box">

            <button class="btn-close close-btn" data-bs-dismiss="modal"></button>

            <div class="modal-body">

                <h3 class="text-center mb-4" id="authTitle">Login to CareerHunt</h3>

                <!-- LOGIN FORM -->

                <form id="loginForm">

                    <div class="mb-3">
                        <label>Username</label>
                        <input type="text" class="form-control" placeholder="Username">
                    </div>

                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" class="form-control" placeholder="Password">
                    </div>

                    <div class="d-flex justify-content-between mb-3 small">
                        <div>
                            <input type="checkbox"> Remember me
                        </div>

                        <a href="#">Forgot password?</a>
                    </div>

                    <button type="button" class="btn auth-btn" onclick="loginUser()">Log In</button>


                    <p class="switch-text">
                        Don't have an account?
                        <a onclick="showRegister()">Signup</a>
                    </p>

                </form>


                <!-- REGISTER FORM -->

                <form id="registerForm" style="display:none">

                    <!-- USER TYPE -->

                    <div class="user-type">

                        <button type="button" class="type-btn active" onclick="selectType(this)">
                            Candidate
                        </button>

                        <button type="button" class="type-btn" onclick="selectType(this)">
                            Employer
                        </button>

                    </div>

                    <div class="mb-3">
                        <label>Email Address</label>
                        <input type="email" class="form-control" placeholder="Email">
                    </div>

                    <div class="mb-3">
                        <label>Password</label>
                        <input type="password" class="form-control" placeholder="Password">
                    </div>

                    <button type="button" class="btn auth-btn" onclick="registerUser()">Register</button>

                    <p class="switch-text">
                        Already have an account?
                        <a onclick="showLogin()">Login</a>
                    </p>

                </form>

            </div>
        </div>
    </div>
</div>
<script src="script.js"></script>