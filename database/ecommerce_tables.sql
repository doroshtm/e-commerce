create table categoria(
    id_categoria serial primary key,
    nome text not null,
    descricao text
);

create table produto(
    id_produto serial primary key,
    nome text not null,
    preco decimal(10,2) not null,
    descricao text,
    imagem varchar(255) not null,
    excluido boolean default false,
    data_exclusao timestamp,
    codigovisual varchar(50) not null,
    custo decimal(10,2) not null,
    margem_lucro decimal(10,2) not null,
    icms decimal(10,2) not null,
    categoria integer not null,
    quantidade_estoque integer not null,
    foreign key (categoria) references categorias(id_categorias)
);

create table usuario(
    id_usuario serial primary key,
    nome text not null,
    email text not null,
    senha varchar(255) not null,
    data_cadastro timestamp not null,
    telefone varchar(17) not null,
    cpf varchar(14) not null,
    admin boolean default false not null,
    endereco varchar(255),
    cep varchar(9)
);

create table compra(
    id_compra serial primary key,
    data timestamp not null,
    status varchar(255) not null,
    usuario integer not null,
    foreign key (usuario) references usuarios(id_usuario)
);

create table tmp_compra(
    sessao varchar(255) primary key,
    compra integer not null,
    foreign key (compra) references compra(id_compra)
)

create table compra_produto(
    valor decimal(10,2) not null,
    quantidade integer not null,
    produto integer not null,
    compra integer not null,
    PRIMARY KEY (produto, compra),
    FOREIGN KEY (produto) REFERENCES produto (id_produto),
    FOREIGN KEY (compra) REFERENCES compra (id_compra)
)