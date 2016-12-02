<template>
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="panel panel-default">
					<div class="panel-heading">
						General
					</div>
					<div class="panel-body">
						<div class="col-md-8 col-md-2-offset">
							<div class="form-group" :class="{'has-success has-feedback': verified,'has-warning has-feedback': warning,'has-error has-feedback': notVerified}">
							    <label for="exampleInputEmail2">Email</label>
							    <input type="email" name="email" class="form-control" id="settingsEmail" placeholder="john.doe@example.com" v-model="Email" autocomplete="off">
							    <span :class="{hidden: !verified}" class="glyphicon glyphicon-ok form-control-feedback" aria-hidden="true"></span>
							    <span :class="{hidden: !warning}" class="glyphicon glyphicon-warning-sign form-control-feedback" aria-hidden="true"></span>
							    <span :class="{hidden: !notVerified}" class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>
							    <span :class="{'text-primary': verifying}" id="helpBlock" class="help-block">{{this.emailHelpText}}&nbsp;&nbsp;<div :class="{'hidden': !verifying}" class="loader" style="display: inline-block;"></div></span>
							    <div :class="{hidden: !verified || same}" class="btn btn-success">Save Email</div>
							</div>
						</div>
					</div>
				</div>
				<div class="panel panel-default">
					<div class="panel-heading">
						Notifications
					</div>
					<div class="panel-body">
						<div class="col-md-8 col-md-2-offset">
							<div class="checkbox">
								<label>
							    	<input type="checkbox">Email Notifications
							    </label>
							    <span class="help-block">Check this to receive notifications by email.</span>
							</div>
						</div>
					</div>
				</div>
				<div class="panel panel-default">
					<div class="panel-heading">
						Security
					</div>
					<div class="panel-body">
						<a href="#" class="btn btn-warning">Change Password</a>
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
				same: true
			}
		},
		props: ['user','settings','email'],
		watch: {
		  	Email: function (email) {
		  		this.verified = false;
	    		if (email != null || email !== "") {
	    			if (this.validate(email)) {
	    				this.verifying = true;
	    				this.same = false;
						this.$http.get('/settings/verifyEmail/'+this.Email).then((response) => {
							console.log(response.body);
							if (response.body.verified == true) {
								this.verified = true;
								this.verifying = false;
								this.notVerified = false;
								this.warning = false;
								this.emailHelpText = "Email Address Available";
								if (this.Email == this.email) {
									this.same = true;
								}
							} else {
								this.verified = false;
								this.notVerified = true;
								this.verifying = false;
								this.emailHelpText = "The email has been taken";
								this.warning = false;
							}
						}).then((response) => {
							this.verifying = false;
						});
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
			validate(email) {
				var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
				return re.test(email);
			}
		},
		created() {
			this.Email = this.email;
		}
	}
</script>

<style scoped>
	
</style>