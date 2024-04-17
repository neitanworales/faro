import { DefaultResponse } from "../DefaultResponse";
import { Role } from "../Role";

export class RoleResponse extends DefaultResponse {
    roles? : Role[]
}