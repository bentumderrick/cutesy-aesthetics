<?php
session_start();
$display_name = $_SESSION['user-details']['display_name'] ?? '';
$email = $_SESSION['user-details']['email'] ?? '';
$profile = $_SESSION['user-details']['profile_picture'] ?? '';
$bio = $_SESSION['user-details']['bio'] ?? '';
$username = $_SESSION['user-details']['username'] ?? '';
$phone = $_SESSION['user-details']['phone'] ?? '';
$created = $_SESSION['user-details']['created_at'] ?? '';
$nickname = $_SESSION['user-details']['nickname'] ?? '';
?>
<section class="section-upload">
    <div class="first-container container">
        <div class="first-container-div">
            <div class="share-creativity">
                <h1>Upload New Artwork <span>✨</span></h1>
                <p>Share your creativity with the world.</p>
            </div>
            <div class="help-wrapper">
                <button class="needHelp">Need help?</button>
            </div>
        </div>

        <form class="container second-container" id="uploadForm">
            <input type="hidden" id="action" name="action" value="publish" />
            <input type="hidden" id="visibility" name="visibility" value="public" />
            <div class="field field-title">
                <label for="title">Artwork Title<span>.</span></label>
                <input
                    id="title"
                    name="title"
                    class="title input"
                    type="text"
                    minlength="6"
                    maxlength="60"
                    placeholder="Enter a title for your artwork"
                    aria-label="Artwork title"
                />
            </div>

            <div class="field field-description">
                <label for="description">Description</label>
                <textarea
                    id="description"
                    name="description"
                    placeholder="Describe your artwork, the inspiration, your process, etc."
                    class="input description"
                    aria-label="Artwork description"
                ></textarea>
            </div>

            <div class="category-tags">
                <div class="field field-category">
                    <label for="category">Category<span>.</span></label>
                    <select
                        id="category"
                        class="select-category input"
                        name="category"
                        aria-label="Artwork category"
                    >
                       <option disabled selected hidden value="">
        Select a Category
    </option>

    <option value="art">Art</option>
    <option value="crochet">Crochet</option>
    <option value="sketches">Sketches</option>
    <option value="art-progress">Art Progress</option>
    <option value="personal_space">Personal Space</option>
                    </select>
                </div>

                <div class="field field-tags">
                    <label for="tags">Tags</label>
                    <input
                        id="tags"
                        name="tags"
                        class="input tags"
                        type="text"
                        placeholder="Add tags (e.g. pastel, anime, flowers)"
                        aria-label="Artwork tags"
                    />
                </div>
            </div>
            <div class="field price">
                <div>
                    <label for="price">Price(GHS)</label>
                    <input
                        class="input"
                        type="number"
                        id="price"
                           name="price"
                        placeholder="GHS 0.00"
                    />
                </div>
                <div class="check-p">
                    <input name="not_for_sale" id="not-for-sale" type="checkbox" />
                    <p class="notSale-label">Not for sale</p>
                </div>
            </div>
            <p class="price-detail">Set a price if this artwork is for sale</p>
            <label for="artwork-image">
                <h2>Artwork Image / Media</h2>
                <div class="drop-box">
                    <div>
                        <span class="cloud-upload material-symbols-outlined">
                            cloud_upload
                        </span>
                        <p style="text-align: center; font-size: 1.3rem">
                            Drag & Drop your image here <br />
                            or click <em>click to browse</em>
                        </p>
                      <p style="text-align: center; font-size: 1.3rem">
    Supports: JPG, JPEG, PNG, WEBP, MP4, MOV
</p>
                    </div>
                </div></label>
            <input
                style="display: none"
                id="artwork-image"
                type="file"
                hidden="true"
                name="upload_file"
                accept="image/png,image/jpeg,image/webp,image/gif, video/mp4, video/mov"
            />
            <h2>
                    Visibility</h2>
            <div class="visibility">
                
                    <div class="public public-private">
                        <div style="width: 25px !important;" class="check">
                           
                        </div>
                      <div>  <p>Public</p>
                        <p>Anyone can see this post</p></div>
                    </div>
                    <div class="private public-private">
                        <div class="check">
                        
                        </div><div>
                        <p>Private</p>
                        <p>Only you can see this post</p>
                    </div></div>
                    <div></div>
                </div>
            </form>
             <div class="field field-submit">
            <!-- reserved for your submit button/actions -->
            <button type="button" id="publishBtn" class="publish"> Publish Artwork</button><button type="button" id="draftBtn" class="draft">Save as Draft</button> <button type="button" id="cancelBtn" class="cancel">Cancel</button>
        </div>
        </div>
       
    <div class="third-container">
      <h2>Preview</h2>
      <p>This is how your post will appear in the feed.</p>
      <div>
        <div class="nameProfile-preview"><div >
        <img class="profilePic-preview" src="<?php
        echo htmlspecialchars($profile);
        ?>" alt="profile-picture"></div><div>
        <h2><?=
        htmlspecialchars($display_name)
        ?></h2>
        <p>Just now</p></div></div><div class="artworkPreview-container">
    <img class="artwork-preview"  id="artwork-preview" alt="Artwork preview">
    <video class="video-preview"  id="video-preview" controls></video>
</div>
       <div class="live-preview"> <h2 class="previews" id="image-title" >Title</h2> <h2 id="price-preview" class="previews">Price</h2></div>
       <p class="description-preview previews">Description Preview</p>
      </div>
      <div class="tips"><p>Tips</p>
      <ul>
        <li>Use high-quality images for better visibility</li>
        <li>Write a clear description to connect with your audience</li>
        <li>Add relevant tags to help people discover your art</li>
      </ul>
      </div>
    </div>
    <div id="nickname" data-set="<?php echo htmlspecialchars($nickname); ?>"></div>
</section>
