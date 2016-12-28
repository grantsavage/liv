<template>
	<div class="container">
		<!-- Modal -->
		<div class="modal fade" tabindex="-1" role="dialog" id="remove">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		        <h4 class="modal-title text-center">Are You Sure You Want to Delete You Account?</h4>
		      </div>
		      <div class="modal-body text-center">
		        	<h4 class="">Enter Your Password To Confirm</h4>
		        	<input type="password" class="form-control" id="confirmRemovePassword" style="margin-bottom: 20px;">
		        	<div class="alert alert-danger hidden" role="alert" id="deleteAlert"></div>
		      </div>
		      <div class="modal-footer">
		      	<div class="text-center">
		        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		        <button @click="removeAccount" type="button" class="btn btn-danger" :class="{disabled: isRemoving}">Delete Account&nbsp;<div v-show="isRemoving" class="button-loader" style="display: inline-block;"></div></button>
		        </div>
		      </div>
		    </div>
		  </div>
		</div>
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="panel panel-default">
					<div class="panel-heading">
						General
					</div>
					<div class="panel-body">
						<div class="col-md-8">
							<div 
								class="form-group" 
								:class="{'has-success has-feedback': verified,'has-warning has-feedback': warning,'has-error has-feedback': notVerified}">
							    <label for="exampleInputEmail2">
							    	Email
							    </label>
							    <input 
							    	type="email" 
							    	name="email" 
							    	class="form-control" 
							    	id="settingsEmail" 
							    	placeholder="john.doe@example.com" 
							    	v-model="Email" 
							    	autocomplete="off" 
							    	debounce="3000">
							    <span 
							    	:class="{hidden: !verified}" 
							    	class="glyphicon glyphicon-ok form-control-feedback" 
							    	aria-hidden="true">
							    </span>
							    <span 
							    	:class="{hidden: !warning}" 
							    	class="glyphicon glyphicon-warning-sign form-control-feedback" 
							    	aria-hidden="true">
							    </span>
							    <span 
							    	:class="{hidden: !notVerified}" 
							    	class="glyphicon glyphicon-remove form-control-feedback" 
							    	aria-hidden="true">
							    </span>
							    <span 
							    	:class="{'text-primary': verifying}" 
							    	id="helpBlock" 
							    	class="help-block">
							    		{{this.emailHelpText}}&nbsp;&nbsp;
							    	<div 
							    		:class="{'hidden': !verifying}" 
							    		class="loader" 
							    		style="display: inline-block;">
							    	</div>
							    </span>
							    <div 
							    	:class="{hidden: !verified || same}" 
							    	class="btn btn-success">
							    		Save Email
							    </div>
							</div>
						</div>
					</div>
				</div>
				<div class="panel panel-default">
					<div class="panel-heading">
						Notifications
					</div>
					<div class="panel-body">
						<div class="col-md-8">
							<div class="checkbox">
								<label>
							    	<input type="checkbox" v-model="emailNotifications">Email Notifications
							    </label>
							    <span class="help-block">Check this to receive notifications by email.</span>
							</div>
							<hr>
							<div class="checkbox">
								<label>
							    	<input type="checkbox" v-model="pushNotifications">Push Notifications
							    </label>
							    <span class="help-block">Check this to receive instant push notifications.</span>
							</div>
							<button class="btn btn-primary" @click="saveSettings">Save</button>
						</div>
					</div>
				</div>
				<div class="panel panel-default">
					<div class="panel-heading">
						Security
					</div>
					<div class="panel-body">
						<div class="col-md-8">
							<button 
								v-show="!showChangePassoword" 
								@click="showChangePassoword = true" 
								type="submit" 
								class="btn btn-warning">
									Change Password
								</button>
							<div v-show="showChangePassoword">
								<div class="form-group">
									<label for="current">Current Password</label>
									<input class="form-control" type="Password" id="current">
								</div>
								<div class="form-group">
									<label for="new">New Password</label>
									<input class="form-control" type="Password" id="new">
								</div>
								<div class="form-group">
									<label for="confirm">Confirm New Password</label>
									<input class="form-control" type="Password" id="confirm">
								</div>
								<button 
									@click="changePassword" 
									type="submit" 
									class="btn btn-warning" 
									:class="{disabled: isChanging}">
										Change Password&nbsp;
									<div 
										class="button-loader" 
										v-show="isChanging" 
										style="display: inline-block;">
									</div>
								</button>
								<div class="alert alert-danger hidden" role="alert" id="changeAlert"></div>
							</div>
						</div>
					</div>
				</div>
				<div class="panel panel-default">
					<div class="panel-heading">
						Delete Account
					</div>
					<div class="panel-body center-block">
						<div class="col-md-6 col-md-offset-3">
							<button 
								@click="showRemoveModal" 
								class="btn btn-block btn-danger">
									Delete Account
							d</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				Settings: {},
				User: {},
				verifying: false,
				verified: true,
				notVerified: false,
				warning: false,
				emailHelpText: "Email Verified",
				Email: null,
				same: true,
				emailNotifications: null,
				pushNotifications: null,
				showChangePassoword: false,
				isRemoving: false,
				isChanging: false
			}
		},

		props: ['user','settings','email'],

		watch: {
			/*
			 * Watch for input changes from the email field
			 */
		  	Email: function (email) {

		  		// Setup
		  		this.verified = false;

		  		// Check if email is null or emptys
	    		if (email != null || email !== "") {

	    			// Validate email
	    			if (this.validate(email)) {

	    				// Setup
	    				this.verifying = true;
	    				this.same = false;

	    				// Create validation request
						this.$http.get('/settings/verifyEmail/'+this.Email).then((response) => {

							// If email is verified
							if (response.body.verified == true) {

								this.verified = true;
								this.verifying = false;
								this.notVerified = false;
								this.warning = false;
								this.emailHelpText = "Email Address Available";

							// If email is equal to current email
							} else if (this.Email == this.email) {

								this.same = true;
								this.emailHelpText = "Email Verified";
								this.verified = true;

							// If email is not verified
							} else {

								this.verified = false;
								this.notVerified = true;
								this.verifying = false;
								this.emailHelpText = "The email has been taken";
								this.warning = false;

							}
						
						// Handle response error
						}).then((response) => {
							this.verifying = false;
						});

					// Reset UI
	    			} else {
	    				this.verified = false;
	    				this.notVerified = false;
	    				this.warning = true;
	    				this.emailHelpText = "Not a valid email address"
	    				this.verifying = false;
	    			}
	    		}	
		 	}
		},
		methods: {
			/*
			 * Validation for email. (Correct format)
			 */
			validate(email) {
				var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
				return re.test(email);
			},

			/*
			 * Request to server to save current settings
			 */
			saveSettings() {

				// Create data
				var data = new FormData();

				// Set data
				data.append('emailNotifications',this.emailNotifications.toString());

				// Create request
				this.$http.post('/settings',data).then((response) => {

					// Alert user
					swal({title:"Settings Updated", type:"success",timer: 2000,showCloseButton: false,showConfirmButton: false});

				// Handle response error
				}).then((response) => {
					// Handle error
				});
			},

			/*
			 * Request to change user's password
			 */
			changePassword() {
				// Setup
				this.isChanging = true;

				// Create data
				var data = new FormData();

				// Set data
				data.append('current', $("#current").val());
				data.append('new', $("#new").val());
				data.append('confirm', $("#confirm").val());

				// Create request
				this.$http.post('settings/account/password',data).then((response) => {

					// Setup
					this.isChanging = false;

					// Handle error if there is one
					if (response.body.error) {

						// Show error
						$("#changeAlert").removeClass("hidden").text(response.body.error);

					//  If no error, show success
					} else {

						// Alert user
						swal({type: 'success',title: 'Password Successfully Updated',timer: 3000,showCloseButton: false, showConfirmButton: false});

						this.showChangePassoword = false;
					}

				// Handle response error
				}).then((response) => {

					// Setup
					this.isChanging = false;

					// Check for response
					if (response) {

						// Check for specific error
						if (response.body.error) {
							$("#changeAlert").removeClass("hidden").text(response.body.error);

						// Check for generic error
						} else {
							$("#changeAlert").removeClass("hidden").text("There was a problem changing your password.");
						}
					}
				});
			},

			/*
			 * Function for showing the account deletion modal
			 */
			showRemoveModal() {
				$('#remove').modal('show');
				$('#confirmRemovePassword').focus();
				$('#confirmRemovePassword').focus();
			},

			/*
			 * Request to delete user's account
			 */
			removeAccount() {
				// Check if password field is null or empty
				if ($("#confirmRemovePassword").val() == null || $("#confirmRemovePassword").val() == '') {

					// Show error
					$("#deleteAlert").removeClass("hidden").text("Please Enter your password");

					return;
				}

				// Setup
				this.isRemoving = true;
				$("#deleteAlert").addClass("hidden");

				// Create data
				var data = new FormData();

				// Set data
				data.append('password',$("#confirmRemovePassword").val());

				// Create request
				this.$http.post('/settings/account/remove', data).then((response) => {

					// Check for error
					if (response.body.error) {

						// Show alert
						$("#deleteAlert").removeClass("hidden").text(response.body.error);
						this.isRemoving = false;

					// If there are no errors
					} else {

						// Rest UI
						this.isRemoving = false;

						// If response removal value is true, show success
						if (response.body.removal == true) {

							// Hide modal
							$('#remove').modal('hide');

							// Alert user
							swal({type: 'success',title: 'Account Successfully Deleted',showConfirmButton: false, showCloseButton: false});

							// Redirect to index
							setTimeout(function() {
								window.location = '/';
							},3000);
							
						}
					}

				// Handle response error
				}, (response) => {
						this.isRemoving = false;
						if (response.body.error) {
							$("#deleteAlert").removeClass("hidden").text(response.body.error);
						} else {
							$("#deleteAlert").removeClass("hidden").text("There was a problem removing your account.");
						}
						
				});
			}
		},
		/*
		 * Executed when view is mounted
		 */
		created() {
			// Set up settings values
			this.emailNotifications = (this.settings.emailNotifications === 'true');
			this.pushNotifications = (this.settings.pushNotifications === 'true');
			this.Email = this.email;
		}
	}
</script>