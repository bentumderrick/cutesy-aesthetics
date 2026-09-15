<?php
session_start();
$display_name = $_SESSION['user-details']['display_name'] ?? '';
$email = $_SESSION['user-details']['email'] ?? '';
$profile = $_SESSION['user-details']['profile_picture'] ?? '';
$bio = $_SESSION['user-details']['bio'] ?? '';
$username = $_SESSION['user-details']['username'] ?? '';
$phone = $_SESSION['user-details']['phone'] ?? '';
$created = $_SESSION['user-details']['created_at'] ?? '';
?>
  
                <section>
                    <div>
                        <h1>My Profile</h1>
                        <p>
                            Manage your personal information and account details
                        </p>
                    </div>
                    <div class="profile-layout">
                        <div class="main-edit-section">
                            <form id="profileForm" enctype="multipart/form-data">
                                <div>
                                    <div class="profile-area">
                                        <div>
                                            <p><b>Profile Picture</b></p>
                                            <label class="profile-picture-wrapper" for="profile_picture">
                                                <img
                                                    class="profile-edit"
                                                    src="<?= htmlspecialchars($profile) ?>"
                                                    alt="<?= htmlspecialchars($display_name) ?>'s profile picture"
                                                >
                                                <div class="camera-btn">
                                                    <span class="material-symbols-outlined">photo_camera</span>
                                                </div>
                                            </label>
                                            <input
                                                type="file"
                                                id="profile_picture"
                                                hidden
                                                name="profile_picture"
                                                accept="image/png,image/jpeg,image/webp,image/gif"
                                            >
                                        </div>
                                        <div>
                                            <p style="color:rgba(115,115,115, 0.8)">JPG, PNG, or GIF, Max size 2mb</p>
                                            <label for="profile_picture" class="change-picture">
                                                <span class="material-symbols-outlined">upload</span>
                                                Change Picture
                                            </label><br>
                                            <a style="margin-top: 8px; text-decoration: underline; color: var(--reddish);" href="#"  id="removeProfileBtn">Remove</a>
                                        </div>
                                    </div>
                              
                                    <p><b>Personal Information</b></p>
                                  
                                    <div class="edit-container">
                                        <div style="display:flex; flex-direction: column; gap:20px; ">
                                            <div>
                                                <label for="display_name">Display Name</label>
                                                <input
                                                    class="edit"
                                                    type="text"
                                                    name="display_name"
                                                    id="display_name"
                                                    value="<?= htmlspecialchars($display_name) ?>"
                                                />
                                            </div>
                                            <div>
                                                <label for="email">Email Address</label>
                                                <input
                                                    class="edit"
                                                    autocomplete="email"
                                                    name="email"
                                                    id="email"
                                                    value="<?= htmlspecialchars($email) ?>"
                                                />
                                            </div>
                                        </div>
                                        <div style="display:flex; flex-direction: column; gap:20px; ">
                                            <div>
                                                <label for="username">Username</label>
                                                <input
                                                    autocorrect="off" 
                                                    autocapitalize="off" 
                                                    class="edit" 
                                                    type="text"
                                                    name="username"
                                                    id="username"
                                                    value="<?= htmlspecialchars($username) ?>"
                                                    autocomplete="username"
                                                />
                                            </div>
                                            <div>
                                                <label for="phone">Phone Number</label>
                                                <!-- Removed regex filter to allow '+' and formatting -->
                                                <input
                                                    type="tel"
                                                    name="phone"
                                                    id="phone"
                                                    value="<?= htmlspecialchars($phone);?>"
                                                    autocomplete="tel"
                                                >
                                            </div>
                                        </div>
             
                                        <div class="bio-group">
                                            <label for="bio">Bio</label>
                                            <textarea
                                                class="edit"
                                                name="bio"
                                                id="bio"><?= htmlspecialchars($bio) ?></textarea>
                                        </div>
                                    </div>
                                    <div class="btn-contain">
                                        <button type="submit" class="edit-appy-btn">Save Changes</button>
                                        <button type="reset" class="edit-appy-btn">Cancel</button>
                                    </div>
                                </div>
                            </form>   
                        </div>
                        <div class="more-info">
                            <div class="account-overview">
                                <p><b>Account Overview</b></p>
                                <div class="overview-item">
                                    <span class="material-symbols-outlined">calendar_month</span>
                                    <div>
                                        <p>Member Since</p>
                                        <p><?= htmlspecialchars($created) ?></p>
                                    </div>
                                </div>
                                <div class="overview-item">
                                    <span class="material-symbols-outlined">verified</span>
                                    <div>
                                        <p>Email Verified</p>
                                        <p>Yes</p>
                                    </div>
                                </div>
                                <div class="overview-item">
                                    <span class="material-symbols-outlined">check_circle</span>
                                    <div>
                                        <p>Account Status</p>
                                        <p>Active</p>
                                    </div>
                                </div>
                            </div>
                            <div class="quick-links">
                                <h3>Quick Links</h3>
                                <div class="link">
                                    <a style="display:inline-flex; justify-content: space-between;width:100%;" href="#">Change Password<span>></span></a>
                                </div>
                                <div class="link">
                                    <a style="display:inline-flex; justify-content: space-between;width:100%;" href="#">Manage Addresses<span>></span></a>
                                </div>
                                <div class="link">
                                    <a style="display:inline-flex; justify-content: space-between;width:100%;" href="#">Notification Settings<span>></span></a>
                                </div>
                                <div class="link">
                                    <a style="display:inline-flex; justify-content: space-between;width:100%;" href="#">Payment Methods<span>></span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </section>
        
