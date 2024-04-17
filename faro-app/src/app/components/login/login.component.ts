import { Component } from '@angular/core';
import { FormBuilder, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { LoginDao } from 'src/app/api/dao/LoginDao';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent {

  constructor(private router: Router, 
    private loginDao: LoginDao, private formBuilder: FormBuilder){}

  loginForm = this.formBuilder.group({
    email: ['', Validators.required],
    password: ['', Validators.required],
  });

  ngOnInit(): void {
    if (this.loginDao.validarSession()) {
      this.router.navigate(['/dashboard']);
    }
  }

  onSubmit() {
    if (this.loginForm.valid) {
      this.loginDao.login(this.loginForm.controls['email'].value!, 
                          this.loginForm.controls['password'].value!).subscribe(
        result => {
          if (result.code == 200) {
            console.log(result.usuario);
            localStorage.setItem('session', JSON.stringify(result.usuario));
            this.router.navigate(['/dashboard']);
          }
        }
      );
    }
  }

}
