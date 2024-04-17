import { Component } from '@angular/core';
import { Validators, FormBuilder } from '@angular/forms';

@Component({
  selector: 'app-first-time',
  templateUrl: './first-time.component.html',
  styleUrls: ['./first-time.component.css']
})
export class FirstTimeComponent {

  constructor(private formBuilder: FormBuilder){}

  formFirstTime = this.formBuilder.group({
    nombre: ['', Validators.required],
    deDondeViene: [''],
    comoSeEntero: [''],
    telefono: ['', [Validators.pattern("[0-9 ]")]],
  });

  onSubmit(){
    console.log("Submit");
  }
}
