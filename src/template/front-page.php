<?php get_header(); ?>

    <?php while ( have_posts() ) : the_post(); ?>

	<div id="header">
		<img src="wp-content/themes/portfolio/images/Rough Logo_Favicon.svg" id="header-logo">
	</div>
    <canvas class="connecting-dots" id="connecting-dots"></canvas>
    <div class="fixed">
        <div id="logo-container">
            <div id="reset-size">
                <img src="wp-content/themes/portfolio/images/S-sharp-hollow.svg" id="s">
                <p id="am">am</p>
                <div class="line"></div>
                <img src="wp-content/themes/portfolio/images/M-hollowed.svg" id="m">
                <p id="uir"> uir</p>
            </div>
            <div id="title">
                <h1>&#60;<?php the_title(); ?> /&#62;</h1>
            </div>
        </div>
    </div>
    <div class="black-rollover scrolling">
        <div class="fixed">
            <div id="logo-container">
                <div id="reset-size">
                    <img src="wp-content/themes/portfolio/images/S-sharp-hollow-white.svg" id="s">
                    <p id="am">am</p>
                    <div class="line-2"></div>
                    <img src="wp-content/themes/portfolio/images/M-hollowed-white.svg" id="m">
                    <p id="uir"> uir</p>
                </div>
                <div id="title">
                    <h1>&#60;<?php the_title(); ?> /&#62;</h1>
                </div>
            </div>
        </div>
    </div>
    <!-- About start -->
    <div class="about-container scrolling">
        <div id="about">
            <div id="about-description">
                <?php the_content(); ?>

                <div id="contact">
                    <div class="contact-item">
                        <a href="mailto:sam.muir59@gmail.com">
                            <svg id="gmail" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                            viewBox="0 0 216 216" style="enable-background:new 0 0 216 216;" xml:space="preserve">
                            <path d="M108,0C48.353,0,0,48.353,0,108s48.353,108,108,108s108-48.353,108-108S167.647,0,108,0z M156.657,60L107.96,98.498
                                L57.679,60H156.657z M161.667,156h-109V76.259l50.244,38.11c1.347,1.03,3.34,1.545,4.947,1.545c1.645,0,3.073-0.54,4.435-1.616
                                l49.374-39.276V156z"/>
                            </svg> 
                            <span>sam.muir59@gmail.com</span>
                        </a>
                    </div>
                    <div class="contact-item">
                        <a href="tel:0211759457">
                            <svg id="phone" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                            viewBox="0 0 612 612" style="enable-background:new 0 0 612 612;" xml:space="preserve">
                            <path d="M478.584,411.876c4.488-4.488,6.12-9.384,4.896-14.688c-0.408-2.446-1.431-4.69-3.063-6.73
                            s-3.468-3.876-5.508-5.508l-72.216-42.229l-0.612-0.609c-2.448-1.227-5.712-1.836-9.792-1.836c-6.528,0-11.628,2.039-15.3,6.117
                            l-21.42,21.422c-1.227,1.225-2.856,1.837-4.896,1.837c-4.896-0.816-10.812-3.063-17.748-6.732
                            c-5.712-3.264-12.954-7.854-21.726-13.771c-8.772-5.916-18.87-14.588-30.294-26.012c-8.16-8.565-15.708-17.136-22.644-25.704
                            c-5.712-6.936-11.016-14.484-15.912-22.644s-7.548-15.3-7.956-21.42c0-2.04,0.612-3.672,1.836-4.896l18.36-18.36
                            c3.264-3.264,5.304-7.242,6.12-11.934s0.204-9.078-1.836-13.158l-39.78-74.664c-3.264-6.528-8.16-9.792-14.688-9.792
                            c-4.08,0-8.16,1.632-12.24,4.896l-48.96,49.572c-2.448,2.04-4.59,4.998-6.426,8.874s-2.958,7.446-3.366,10.71v15.3
                            c0,0,2.652,12.648,7.956,37.944s14.586,44.268,27.846,56.916c13.26,12.647,32.742,36.516,58.446,71.604
                            c22.032,22.032,42.636,39.372,61.812,52.021c19.176,12.646,36.414,22.134,51.713,28.458c15.301,6.321,28.255,10.302,38.86,11.934
                            c10.608,1.632,18.156,2.448,22.646,2.448c2.04,0,3.672-0.104,4.896-0.307c1.227-0.203,2.04-0.308,2.448-0.308
                            c3.264-0.408,6.73-1.53,10.404-3.366c3.672-1.836,6.729-3.978,9.18-6.426L478.584,411.876L478.584,411.876z M306,0
                            c42.024,0,81.702,8.058,119.034,24.174s69.768,37.944,97.308,65.484s49.368,59.976,65.484,97.308S612,263.976,612,306
                            c0,28.152-3.672,55.284-11.016,81.396c-7.347,26.109-17.646,50.487-30.906,73.134c-13.26,22.644-29.172,43.248-47.736,61.812
                            c-18.562,18.564-39.168,34.479-61.812,47.736c-22.646,13.26-47.022,23.562-73.136,30.906C361.284,608.328,334.152,612,306,612
                            s-55.284-3.672-81.396-11.016c-26.112-7.347-50.49-17.646-73.134-30.906s-43.248-29.172-61.812-47.736
                            c-18.564-18.562-34.476-39.168-47.736-61.812c-13.26-22.646-23.562-47.022-30.906-73.135C3.672,361.284,0,334.152,0,306
                            s3.672-55.284,11.016-81.396s17.646-50.49,30.906-73.134s29.172-43.248,47.736-61.812s39.168-34.476,61.812-47.736
                            s47.022-23.562,73.134-30.906S277.848,0,306,0z"/>
                            </svg>
                            <span>021 175 9457</span>
                        </a>
                    </div>
                    <div class="contact-item">
                        <a href="https://www.facebook.com/sam.muir.71" target="_blank">
                            <svg id="facebook" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                            viewBox="0 0 49.652 49.652" style="enable-background:new 0 0 49.652 49.652;"
                            xml:space="preserve">
                            <path d="M24.826,0C11.137,0,0,11.137,0,24.826c0,13.688,11.137,24.826,24.826,24.826c13.688,0,24.826-11.138,24.826-24.826
                                C49.652,11.137,38.516,0,24.826,0z M31,25.7h-4.039c0,6.453,0,14.396,0,14.396h-5.985c0,0,0-7.866,0-14.396h-2.845v-5.088h2.845
                                v-3.291c0-2.357,1.12-6.04,6.04-6.04l4.435,0.017v4.939c0,0-2.695,0-3.219,0c-0.524,0-1.269,0.262-1.269,1.386v2.99h4.56L31,25.7z
                                "/>
                            </svg> 
                            <span>sam.muir.71</span>
                        </a>
                    </div>
                    <div class="contact-item">
                        <a href="https://twitter.com/SamMuir59" target="_blank">
                            <svg id="twitter" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                            viewBox="0 0 49.652 49.652" style="enable-background:new 0 0 49.652 49.652;"
                            xml:space="preserve">
                            <path d="M24.826,0C11.137,0,0,11.137,0,24.826c0,13.688,11.137,24.826,24.826,24.826c13.688,0,24.826-11.138,24.826-24.826
                            C49.652,11.137,38.516,0,24.826,0z M35.901,19.144c0.011,0.246,0.017,0.494,0.017,0.742c0,7.551-5.746,16.255-16.259,16.255
                            c-3.227,0-6.231-0.943-8.759-2.565c0.447,0.053,0.902,0.08,1.363,0.08c2.678,0,5.141-0.914,7.097-2.446
                            c-2.5-0.046-4.611-1.698-5.338-3.969c0.348,0.066,0.707,0.103,1.074,0.103c0.521,0,1.027-0.068,1.506-0.199
                            c-2.614-0.524-4.583-2.833-4.583-5.603c0-0.024,0-0.049,0.001-0.072c0.77,0.427,1.651,0.685,2.587,0.714
                            c-1.532-1.023-2.541-2.773-2.541-4.755c0-1.048,0.281-2.03,0.773-2.874c2.817,3.458,7.029,5.732,11.777,5.972
                            c-0.098-0.419-0.147-0.854-0.147-1.303c0-3.155,2.558-5.714,5.713-5.714c1.644,0,3.127,0.694,4.171,1.804
                            c1.303-0.256,2.523-0.73,3.63-1.387c-0.43,1.335-1.333,2.454-2.516,3.162c1.157-0.138,2.261-0.444,3.282-0.899
                            C37.987,17.334,37.018,18.341,35.901,19.144z"/>
                            </svg>
                            <span>SamMuir59</span>
                        </a>
                    </div>
                    <div class="contact-item">
                        <a href="https://www.linkedin.com/in/samuel-muir-a6b54164/" target="_blank">
                            <svg version="1.1" id="linkedIn" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                            viewBox="0 0 49.652 49.652" style="enable-background:new 0 0 49.652 49.652;" xml:space="preserve">
                            <path d="M29.35,21.298c-2.125,0-3.074,1.168-3.605,1.988v-1.704h-4.002c0.052,1.128,0,12.041,0,12.041h4.002v-6.727
                            c0-0.359,0.023-0.72,0.131-0.977c0.29-0.72,0.948-1.465,2.054-1.465c1.448,0,2.027,1.104,2.027,2.724v6.442h4.002h0.001v-6.905
                            C33.958,23.019,31.983,21.298,29.35,21.298z M25.742,23.328h-0.025c0.008-0.014,0.02-0.027,0.025-0.041V23.328z"/>
                            <rect x="15.523" y="21.582" width="4.002" height="12.041"/>
                            <path d="M24.826,0C11.137,0,0,11.137,0,24.826c0,13.688,11.137,24.826,24.826,24.826c13.688,0,24.826-11.138,24.826-24.826
                            C49.652,11.137,38.516,0,24.826,0z M37.991,36.055c0,1.056-0.876,1.91-1.959,1.91H13.451c-1.08,0-1.957-0.854-1.957-1.91V13.211
                            c0-1.055,0.877-1.91,1.957-1.91h22.581c1.082,0,1.959,0.856,1.959,1.91V36.055z"/>
                            <path d="M17.551,15.777c-1.368,0-2.264,0.898-2.264,2.08c0,1.155,0.869,2.08,2.211,2.08h0.026c1.396,0,2.265-0.925,2.265-2.08
                            C19.762,16.676,18.921,15.777,17.551,15.777z"/>
                            </svg>
                            <span>Samuel-muir</span>
                        </a>
                    </div>
                    <div class="contact-item">
                        <a href="https://github.com/Cactusman07" target="_blank">
                            <svg version="1.1" id="gitHub" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                            viewBox="0 0 438.549 438.549" style="enable-background:new 0 0 438.549 438.549;"                     xml:space="preserve">
                            <path d="M409.132,114.573c-19.608-33.596-46.205-60.194-79.798-79.8C295.736,15.166,259.057,5.365,219.271,5.365
                            c-39.781,0-76.472,9.804-110.063,29.408c-33.596,19.605-60.192,46.204-79.8,79.8C9.803,148.168,0,184.854,0,224.63
                            c0,47.78,13.94,90.745,41.827,128.906c27.884,38.164,63.906,64.572,108.063,79.227c5.14,0.954,8.945,0.283,11.419-1.996
                            c2.475-2.282,3.711-5.14,3.711-8.562c0-0.571-0.049-5.708-0.144-15.417c-0.098-9.709-0.144-18.179-0.144-25.406l-6.567,1.136
                            c-4.187,0.767-9.469,1.092-15.846,1c-6.374-0.089-12.991-0.757-19.842-1.999c-6.854-1.231-13.229-4.086-19.13-8.559
                            c-5.898-4.473-10.085-10.328-12.56-17.556l-2.855-6.57c-1.903-4.374-4.899-9.233-8.992-14.559
                            c-4.093-5.331-8.232-8.945-12.419-10.848l-1.999-1.431c-1.332-0.951-2.568-2.098-3.711-3.429c-1.142-1.331-1.997-2.663-2.568-3.997
                            c-0.572-1.335-0.098-2.43,1.427-3.289c1.525-0.859,4.281-1.276,8.28-1.276l5.708,0.853c3.807,0.763,8.516,3.042,14.133,6.851
                            c5.614,3.806,10.229,8.754,13.846,14.842c4.38,7.806,9.657,13.754,15.846,17.847c6.184,4.093,12.419,6.136,18.699,6.136
                            c6.28,0,11.704-0.476,16.274-1.423c4.565-0.952,8.848-2.383,12.847-4.285c1.713-12.758,6.377-22.559,13.988-29.41
                            c-10.848-1.14-20.601-2.857-29.264-5.14c-8.658-2.286-17.605-5.996-26.835-11.14c-9.235-5.137-16.896-11.516-22.985-19.126
                            c-6.09-7.614-11.088-17.61-14.987-29.979c-3.901-12.374-5.852-26.648-5.852-42.826c0-23.035,7.52-42.637,22.557-58.817
                            c-7.044-17.318-6.379-36.732,1.997-58.24c5.52-1.715,13.706-0.428,24.554,3.853c10.85,4.283,18.794,7.952,23.84,10.994
                            c5.046,3.041,9.089,5.618,12.135,7.708c17.705-4.947,35.976-7.421,54.818-7.421s37.117,2.474,54.823,7.421l10.849-6.849
                            c7.419-4.57,16.18-8.758,26.262-12.565c10.088-3.805,17.802-4.853,23.134-3.138c8.562,21.509,9.325,40.922,2.279,58.24
                            c15.036,16.18,22.559,35.787,22.559,58.817c0,16.178-1.958,30.497-5.853,42.966c-3.9,12.471-8.941,22.457-15.125,29.979
                            c-6.191,7.521-13.901,13.85-23.131,18.986c-9.232,5.14-18.182,8.85-26.84,11.136c-8.662,2.286-18.415,4.004-29.263,5.146
                            c9.894,8.562,14.842,22.077,14.842,40.539v60.237c0,3.422,1.19,6.279,3.572,8.562c2.379,2.279,6.136,2.95,11.276,1.995
                            c44.163-14.653,80.185-41.062,108.068-79.226c27.88-38.161,41.825-81.126,41.825-128.906
                            C438.536,184.851,428.728,148.168,409.132,114.573z"/>
                            </svg>
                            <span>Cactusman07</span>
                        </a>
                    </div>
                    <div class="contact-item">
                        <a href="https://teamtreehouse.com/sammuir" target="_blank">
                            <svg alt="Treehouse" preserveAspectRatio="xMinYMin meet" id="treehouse" viewBox="0 0 30 32">
                            <path d="M26.5 5.9c-1.1-0.6-2.8 0.4-3.9 2.2l-1.9 3.2c-0.7 1.3-0.6 3 0.3 4.2l0.1 0.1c0.9 1.2 2.1 2.4 2.4 2.8 0.2 0.2 0.4 0.5 0.5 0.9 0.3 1.1-0.3 2.3-1.5 2.6 -1.1 0.3-2.3-0.3-2.7-1.5 -0.1-0.3-0.1-0.6-0.1-0.8 0.1-0.5-0.1-1.2-0.8-2 -0.7-0.7-2.1 0.7-2.5 2.1v0.1c-0.4 1.4-0.7 2.7-0.6 2.8 0.1 0.1 0.1 0.1 0.1 0.2 0.6 1.2 0.2 2.6-1 3.2 -1.2 0.6-2.7 0.2-3.3-1s-0.2-2.6 1-3.2c0.1 0 0.1-0.1 0.2-0.1 0.1 0 0.3-0.6 0.6-1.4 0.2-0.7 0.3-1 0.4-1.3 0.1-0.4 0.3-1.4 0.2-1.8 -0.1-0.5-0.6-0.5-1.2-0.2 -0.3 0.2-0.9 0.7-1.1 0.9 -0.5 0.4-0.9 1-1.1 1.5 -0.1 0.2-0.3 0.5-0.5 0.7 -0.9 0.7-2.3 0.6-3-0.3 -0.7-0.9-0.6-2.2 0.3-3 0.3-0.2 0.6-0.4 0.9-0.4 0.5-0.1 2.4-1.2 3.5-2 0.2-0.1 0.5-0.4 0.6-0.5 0.3-0.4 0-0.7-0.3-0.7 -0.9 0.1-1.9 0.2-2.1 0.5 -0.1 0.1-0.2 0.3-0.4 0.4 -0.9 0.6-2.1 0.4-2.7-0.4 -0.6-0.8-0.4-2 0.4-2.6 0.4-0.3 0.8-0.4 1.2-0.4 0.7 0 2.5 0.4 4 0.1l0.4-0.1c1.5-0.3 3.3-1.6 4-2.9 0 0 0.7-1.2 1.6-2.7 0.9-1.5 0.8-3.2-0.1-3.7l-1.7-1c-0.9-0.5-2.3-0.5-3.2 0L1.6 7C0.7 7.5 0 8.6 0 9.6v12.8c0 1 0.7 2.2 1.6 2.7l11.9 6.5c0.9 0.5 2.3 0.5 3.2 0l11.8-6.5c0.9-0.5 1.6-1.7 1.6-2.7V9.6c0-1-0.7-2.2-1.6-2.7C26.5 5.9 28.5 7 26.5 5.9z"></path>
                            </svg>
                            <span>Treehouse - online study profile.</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    
    <?php endwhile; wp_reset_query(); ?>

<?php get_footer(); ?>