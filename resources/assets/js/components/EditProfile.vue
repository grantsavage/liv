<template>
	<div class="container">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="panel panel-default">
					<div class="panel-heading">Update Profile</div>
					<div class="panel-body">
						<form action="#" class="form-horizontal" @submit.prevent="update">
							<div class="form-group">	
								<div class="col-sm-8">
									<label for="name" class="control-label">Name</label>
									<input type="text" class="form-control" name="name" id="name" placeholder="Name"  v-model="name">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-8">
									<label for="email" class="control-label">Email</label>
									<input type="email" class="form-control" name="email" id="email" placeholder="Email" v-model="email">
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-8">
									<button id="submitButton" type="submit" class="btn btn-primary">Save</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		data(){
			return {
				name: this.user.name,
				email: this.user.email
			}
		},
		props: [
			'user'
		],
		methods: {
			update(){
				$("#submitButton").text("...").addClass("disabled");
				//$("#submitButton .swal").blur();
				this.$http.post('/profile/edit', {
					name: this.name,
					email: this.email
				},{timeout: 5000}).then((response) => {
					if (response.status === 200) {
						swal({title:"Profile Updated", text:"Your profile has been updated successfully.", type:"success",timer: 3000,showCloseButton: false,showConfirmButton: false});
						$("#submitButton").text("Save").removeClass("disabled");
					} else {
						swal({title: "Whoops...", text: "There was a problem updating your profile. Try again later...", type: "error"});
						//$(".swal").blur();
					}
				}, (response) => {
					swal({title: "Whoops...", text: "There was a problem updating your profile. Try again later...", type: "error"});
						//$("#submitButton").blur();
				});
			}
		}
	}
</script>