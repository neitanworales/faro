import { Role } from "./Role";

export class User {
    id?: BigInteger;
    nombre?: String;
    apellido?: String;
    email?: String;
    roles?: Role[];
    token?: String;
}