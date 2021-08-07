export default class Gate{

    constructor(user){
        this.user = user;
    }

    isAdmin(){
        return this.user.role === 1;
    }

    isUser(){
        return this.user.type === 2;
    }
    
    isAdminOrUser(){
        if(this.user.type === 2 || this.user.type === 1){
            return true;
        }
    }
}

