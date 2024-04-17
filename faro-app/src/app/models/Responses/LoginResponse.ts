import { DefaultResponse } from "../DefaultResponse";
import { User } from "../User";

export class LoginResponse extends DefaultResponse {
    usuario?: User
}