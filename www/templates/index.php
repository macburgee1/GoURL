<?php
    $mode = 'create';
    $formParams = array();
    if (isset($_POST['theURL'])) {
        $formParams = $_POST;
        $mode = !empty($_POST['mode']) ? $_POST['mode'] : 'create';
    } elseif (!empty($goURL) && !empty($goURL['urlID'])) {
        $formParams = $goURL;
        $mode = 'edit';
    }
    $goURLForm = new goURLForm($formParams);

    $submitBtnLabel = $mode === 'edit' ? 'Update URL' : 'Create URL';
    $disabledAlias = $mode === 'edit' ? ' disabled ' : '';
?>
<div class="dcf-bleed dcf-wrapper dcf-pt-8 dcf-pb-8 dcf-d-flex dcf-jc-center">
    <form class="dcf-form dcf-w-max-lg" id="shorten-form" action="<?php echo $lilurl->getBaseUrl() ?>" method="post">
        <input type="hidden" name="mode" value="<?php echo $mode; ?>">
        <input type="hidden" name="id" value="<?php echo $goURLForm->getID(); ?>">
        <div class="dcf-form-controls-inline dcf-mb-5">
            <label for="theURL">Enter the <abbr class="dcf-txt-sm" title="Uniform Resource Locator">URL</abbr> that you want to shorten <small class="dcf-required">Required</small></label>
            <div class="dcf-input-group">
              <input id="theURL" name="theURL" type="text" value="<?php echo (!empty($goURLForm->getLongURL()))?htmlentities($goURLForm->getLongURL(), ENT_QUOTES):'';?>" required oninvalid="this.setCustomValidity('Please provide a URL to redirect to.')" oninput="this.setCustomValidity('')">
            </div>
        </div>
        <?php if ($auth->isAuthenticated()): ?>
            <div>
                <fieldset>
                    <legend class="dcf-bold dcf-txt-lg">Custom Alias</legend>
                    <div class="dcf-form-group">
                        <label>Alias <small class="dcf-pl-1 dcf-txt-xs dcf-italic unl-dark-gray">Optional</small></label><span>
                            <input id="theAlias" name="theAlias" type="text" aria-labelledby="theAliasLabel" aria-describedby="theAliasDesc" value="<?php echo $goURLForm->getID(); ?>" <?php echo $disabledAlias; ?>>
                            <span class="dcf-form-help" id="theAliasDesc" tabindex="-1">For example, <em>admissions</em> for <i><?php echo $_SERVER['HTTP_HOST']; ?>/admissions</i> <strong>(letters, numbers, underscores and dashes only)</strong></span>
                            <?php if ($mode === 'edit') : ?>
                                <span class="dcf-form-help">Note: The Custom Alias is an indentifer and can not be edited.  If you need to update the alias you must delete goURL and recreate with new alias.</span>
                            <?php endif ?>
                        </span>
                    </div>
                </fieldset>
                <fieldset>
                    <legend class="dcf-bold dcf-txt-lg">Google Analytics Campaign Tagging</legend>
                    <div class="dcf-input-checkbox">
                        <input id="with-ga-campaign" name="with-ga-campaign" type="checkbox" value="0" aria-labelledby="with-ga-campaign-label">
                        <label for="with-ga-campaign" id="with-ga-campaign-label">Use Google Analytics Campaign with URL <small class="dcf-pl-1 dcf-txt-xs dcf-italic unl-dark-gray">Optional</small></label>
                    </div>
                    <div id="ga-tagging" style="display:none">
                        <p class="dcf-txt-sm">Add your campaign information here and it will be automatically added to your URL when redirected.</p>
                        <div class="dcf-form-group">
                            <label id="gaNameLabel" for="gaName">Campaign Name <small class="dcf-required">Required</small></label>
                            <span>
                                <input class="ga-required" id="gaName" name="gaName" type="text" aria-labelledby="gaNameLabel" aria-describedby="gaNameDesc" value="<?php echo $goURLForm->getGaName(); ?>">
                                <span class="dcf-form-help" id="gaNameDesc" tabindex="-1">Product, promo code or slogan</span>
                            </span>
                        </div>
                        <div class="dcf-form-group">
                            <label id="gaMediumLabel" for="gaMedium">Medium <small class="dcf-required">Required</small></label>
                            <span>
                                <input class="ga-required" id="gaMedium" name="gaMedium" type="text" aria-labelledby="gaMediumLabel" aria-describedby="gaMediumDesc" value="<?php echo $goURLForm->getGaMedium(); ?>">
                                <span class="dcf-form-help" id="gaMediumDesc" tabindex="-1">Marketing medium: email, web, banner</span>
                            </span>
                        </div>
                        <div class="dcf-form-group">
                            <label id="gaSourceLabel" for="gaSource">Source <small class="dcf-required">Required</small></label>
                            <span>
                                <input class="ga-required" id="gaSource" name="gaSource" type="text" aria-labelledby="gaSourceLabel" aria-describedby="gaSourceDesc" value="<?php echo $goURLForm->getGaSource(); ?>">
                                <span class="dcf-form-help" id="gaSourceDesc" tabindex="-1">Referrer: Google, Facebook, Twitter</span>
                            </span>
                        </div>
                        <div class="dcf-form-group">
                            <label id="gaTermLabel" for="gaTerm">Term</label>
                            <span>
                                <input id="gaTerm" name="gaTerm" type="text" aria-labelledby="gaContentLabel" aria-describedby="gaTermDesc" value="<?php echo $goURLForm->getGaTerm(); ?>">
                                <span class="dcf-form-help" id="gaTermDesc" tabindex="-1">Identify the keywords</span>
                            </span>
                        </div>
                        <div class="dcf-form-group">
                            <label id="gaContentLabel" for="gaContent">Content</label>
                            <span>
                                <input id="gaContent" name="gaContent" type="text" aria-labelledby="gaContentLabel" aria-describedby="gaContentDesc" value="<?php echo $goURLForm->getGaContent(); ?>">
                                <span class="dcf-form-help" id="gaContentDesc" tabindex="-1">Use to differentiate ads (A/B testing)</span>
                            </span>
                        </div>
                    </div>
                </fieldset>
            <?php else: ?>
                <div class="dcf-txt-xs">
                    <h2 class="dcf-txt-h4">Unauthenticated User Notice</h2>
                    <ul>
                        <li>You cannot manage the URL.</li>
                        <li>You cannot define a Custom Alias or Google Analytics Campaign Tagging.</li>
                        <?php if (count($lilurl->getAllowedDomains())): ?>
                        <li>Only allowed <abbr class="dcf-txt-sm" title="Uniform Resource Locator">URL</abbr> domains
                            are: <?php echo implode(", ", $lilurl->getAllowedDomains()); ?>.</li>
                        <?php endif; ?>
                    </ul>
                    <p>Please <a href="./?login">log in</a> if you need any of the above.</p>
                </div>
            <?php endif ?>
            <input class="dcf-mt-6 dcf-btn dcf-btn-primary" id="submit" name="submit" type="submit" value="<?php echo $submitBtnLabel; ?>">
            <?php if ($mode === 'edit') : ?>
                <a class="dcf-btn dcf-btn-secondary dcf-mt-1" href="<?php echo $lilurl->getBaseUrl($goURLForm->getID() . '/reset') ?>" title="Reset redirect count for <?php echo $goURLForm->getID(); ?> Go URL" onclick="return confirm('Are you sure you want to reset the redirect count for \'<?php echo $goURLForm->getID(); ?>\'?');">Reset Redirects</a>
                <button class="dcf-btn dcf-btn-secondary dcf-mt-1" type="button" onclick="return confirm('Are you for sure you want to delete \'<?php echo $goURLForm->getID(); ?>\'?') && submitDelete();">Delete</button>
            <?php endif ?>
        </div>
    </form>
    <?php if ($mode === 'edit') : ?>
    <form id="delete-form" class="dcf-form" action="<?php echo $lilurl->getBaseUrl('a/links') ?>" method="post">
        <input type="hidden" name="urlID" value="<?php echo $goURLForm->getID(); ?>" />
    </form>
    <script>
        function submitDelete() {
            document.getElementById('delete-form').submit();
        }
    </script>
    <?php endif ?>
</div>

<!--Script for displaying Google Analytics Campaign Tagging when checkbox is clicked-->
<script>
    var withGACheckbox = document.getElementById('with-ga-campaign');
    var gaSection = document.getElementById('ga-tagging');
    var gaRequiredVars = document.getElementsByClassName('ga-required');

    withGACheckbox.addEventListener('click', function() {
        if (this.checked) {
            gaSection.style.display = 'block';
            for (var i=0; i<gaRequiredVars.length; i++) {
                var currentElement = document.getElementsByClassName('ga-required')[i].id;
                gaRequiredVars[i].required = true;
                gaRequiredVars[i].disabled = false;

                if(currentElement == 'gaName'){
                    gaRequiredVars[i].setAttribute('oninvalid', 'this.setCustomValidity(\' Please provide a campaign name.\')');
                    gaRequiredVars[i].setAttribute('oninput', 'this.setCustomValidity(\'\')');
                }
                else if(currentElement == 'gaMedium'){
                    gaRequiredVars[i].setAttribute('oninvalid', 'this.setCustomValidity(\' Please provide a marketing medium.\')');
                    gaRequiredVars[i].setAttribute('oninput', 'this.setCustomValidity(\'\')');
                }
                else if(currentElement == "gaSource"){
                    gaRequiredVars[i].setAttribute('oninvalid', 'this.setCustomValidity(\' Please provide a source.\')');
                    gaRequiredVars[i].setAttribute('oninput', 'this.setCustomValidity(\'\')');
                }
            }
        } else{
            gaSection.style.display = 'none';
            for (var i=0; i<gaRequiredVars.length; i++) {
                gaRequiredVars[i].required = false;
                gaRequiredVars[i].disabled = true;
                gaRequiredVars[i].removeAttribute('oninvalid');
                gaRequiredVars[i].removeAttribute('oninput');
                gaRequiredVars[i].value = '';
            }
            document.getElementById('gaTerm').value = '';
            document.getElementById('gaContent').value = '';
        }
    });
    <?php if ($goURLForm->getHasGa()) : ?>
    withGACheckbox.click();
    <?php endif ?>
</script>

<?php
    class goURLForm {
        private $urlID;
        private $longURL;
        private $hasGa = FALSE;
        private $gaName;
        private $gaMedium;
        private $gaSource;
        private $gaTerm;
        private $gaContent;

        public function __construct(array $params) {
            if (isset($params['urlID'])) {
                $this->urlID = $params['urlID'];
            }

            if (isset($params['longURL'])) {
                $urlParts = parse_url($params['longURL']);
                $nonGAQueryString = $this->parseQueryString(trim($urlParts['query']));
                $this->longURL = $urlParts['scheme'] . '://' . $urlParts['host'] . $urlParts['path'];
                if (!empty($nonGAQueryString)) {
                    $this->longURL .= '?' . substr($nonGAQueryString, 0, -1);
                }
            }

            if (isset($params['redirects'])) {
                $this->redirects = $params['redirects'];
            }
        }

        public function getID() {
            return $this->urlID;
        }

        public function getLongURL() {
            return $this->longURL;
        }

        public function getHasGa() {
            return $this->hasGa;
        }

        public function getGaName() {
            return $this->gaName;
        }

        public function getGaMedium() {
            return $this->gaMedium;
        }

        public function getGaSource() {
            return $this->gaSource;
        }

        public function getGaTerm() {
            return $this->gaTerm;
        }

        public function getGaContent() {
            return $this->gaContent;
        }

        private function parseQueryString($query) {
            $nonGAQueryString = '';
            if (empty($query)) { return $nonGAQueryString; }

            $varDefs = explode('&', $query);
            foreach($varDefs as $varDef){
                $parts = explode('=', $varDef);
                if (count($parts) === 2) {
                    $this->setGA($parts[0], $parts[1], $nonGAQueryString);
                } else {
                    $nonGAQueryString .= $varDef . '&';
                }
            }
            return $nonGAQueryString;
        }

        private function setGA($var, $value, &$nonGAQueryString) {
            switch ($var) {
                case 'utm_source':
                    $this->hasGa = TRUE;
                    $this->gaSource = $value;
                    break;
                case 'utm_medium':
                    $this->hasGa = TRUE;
                    $this->gaMedium = $value;
                    break;
                case 'utm_term':
                    $this->hasGa = TRUE;
                    $this->gaTerm = $value;
                    break;
                case 'utm_content':
                    $this->hasGa = TRUE;
                    $this->gaContent = $value;
                    break;
                case 'utm_campaign':
                    $this->hasGa = TRUE;
                    $this->gaName = $value;
                    break;
                default:
                    $nonGAQueryString .= $var . '=' . $value . '&';
            }
        }

    }
